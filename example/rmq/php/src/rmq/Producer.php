<?php
# Producer.php
/**
 * User: Administrator
 * Date: 2017/7/5
 * Time: 10:28
 */

namespace j\mq\rmq;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class JzRabbitMQExample
 * @package rmqTest
 */
class Producer extends Base
{
    private static $instance;

    /**
     * @return Producer
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $data
     * @param string $routingKey
     */
    public function push($data, $routingKey)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $msg = new AMQPMessage($data, array(
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, // ÏûÏ¢³Ö¾Ã
        ));
        $this->getChannel()->basic_publish($msg, $this->exchange, $routingKey);

        $log = " [x] Sent " . $routingKey . ':' . $data;
        if (isset($this->logger)) {
            $this->logger->info($log);
        }
    }
}
