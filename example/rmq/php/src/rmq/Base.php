<?php
# Base.php

/**
 * User: Administrator
 * Date: 2017/7/5
 * Time: 10:28
 */

namespace j\mq\rmq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerAwareTrait;

/**
 * Class Base
 * @package mq
 * @property AMQPChannel $channel
 * @property AMQPStreamConnection $conn
 */
class Base
{
    use LoggerAwareTrait;

    protected $connection;
    protected $channel;
    protected $exchange;
    protected $exchangeType = 'topic';

    /** @var Config */
    public $config;

    /**
     * JzRabbitMQExample constructor.
     * @param string $exchange
     */
    public function __construct($exchange = '', Config $config = null)
    {
        if (!$config) {
            $config = new Config();
        }

        $this->exchange = $exchange ?: $config->exchange;
        $this->config = $config;
    }

    protected function getConnection()
    {
        if (!isset($this->connection)) {
            $this->connection = new AMQPStreamConnection(
                $this->config->host,
                $this->config->port,
                $this->config->user,
                $this->config->password,
                $this->config->vhost ?: '/',
            );
        }
        return $this->connection;
    }

    protected function getChannel()
    {
        if (!isset($this->channel)) {
            $this->channel = $this->getConnection()->channel();
            $this->channel->exchange_declare($this->exchange, $this->exchangeType, false, true, false);
        }
        return $this->channel;
    }

    public function close()
    {
        if (isset($this->channel)) {
            $this->channel->close();
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function info($message)
    {
        if ($this->logger) {
            $this->logger->info($message);
        }
    }

    public function log($level, $message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }
}
