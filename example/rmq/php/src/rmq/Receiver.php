<?php

namespace j\mq\rmq;

/**
 * Class Receiver
 * @package mq
 */
class Receiver extends Base
{
    /**
     * @var array
     */
    protected $queues = [
        'all' => ["#"],
    ];

    /**
     * @var callable
     */
    protected $callback;

    public $autoAct = true;

    public $receiveNum = 1;

    /**
     * @var string
     */
    public $lockFile;

    /**
     * �Ƿ����ź�
     * @var bool
     */
    protected $isProcessSignal = false;

    /**
     * Receiver constructor.
     * @param string $exchange
     */
    public function __construct($exchange = '')
    {
        parent::__construct($exchange);
        $this->callback = [$this, 'process'];
        $this->regSignal();
    }

    protected function regSignal()
    {
        if (!extension_loaded('pcntl')) {
            return;
        }

        $this->isProcessSignal = true;
        $handler = function ($signal) {
            switch ($signal) {
                case \SIGTERM:
                case \SIGUSR1:
                case \SIGINT:
                    // some stuff before stop consumer e.g. delete lock etc
                    if ($this->lockFile && file_exists($this->lockFile)) {
                        unlink($this->lockFile);
                    }
                    pcntl_signal($signal, \SIG_DFL); // restore handler
                    posix_kill(posix_getpid(), $signal); // kill self with signal, see https://www.cons.org/cracauer/sigint.html
                    break;
                case \SIGHUP:
                    // some stuff to restart consumer
                    break;
                default:
                    // do nothing
            }
        };

        pcntl_signal(\SIGTERM, $handler);
        pcntl_signal(\SIGINT, $handler);
        pcntl_signal(\SIGUSR1, $handler);
        pcntl_signal(\SIGHUP, $handler);
    }

    protected function declareQueue(& $queueName, $keys = [])
    {
        // �Ƿ�־ô洢
        $durable = (bool)$queueName;
        $auto_delete = !$queueName;
        list($queueName, ) = $this->getChannel()->queue_declare($queueName, false, $durable, false, $auto_delete);
        if (isset($this->queues[$queueName])) {
            $keys = $this->queues[$queueName];
        }

        if ($keys) {
            foreach ($keys as $key) {
                $this->getChannel()->queue_bind($queueName, $this->exchange, $key);
            }
        }
    }

    /**
     * @param $queueName
     * @param $keys
     * @param null $callback
     */
    public function receive($queueName, $keys, $callback = null)
    {
        // һ�ν���һ����Ϣ
        $this->getChannel()->basic_qos(null, $this->receiveNum, null);

        // �󶨶���
        $this->declareQueue($queueName, $keys);

        // ������Ϣ
        $this->getChannel()->basic_consume($queueName, '', false, false, false, false, function ($msg) use ($callback) {
            $callback = $callback ?: $this->callback;
            $act = call_user_func($callback, $msg, $this);

            $this->disposeSignal();

            if ($act) {
                $this->act($msg);
            }
        });

        while (count($this->channel->callbacks)) {
            $this->disposeSignal();
            $this->channel->wait();
        }
    }

    public function disposeSignal()
    {
        if ($this->isProcessSignal) {
            pcntl_signal_dispatch(); // �����ź�
        }
    }

    /**
     * @param callable $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * ȷ����Ϣ״̬
     * @param $msg
     */
    public function act($msg)
    {
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    /**
     * @param $msg
     * @param $receiver
     */
    public function process($msg, $receiver)
    {
        echo ' [x] ', $msg->body, "\n";
        $this->act($msg);
    }
}
