<?php
#SimpleLogger.php created by stcer@jz at 2025/1/9
namespace j\mq\log;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class SimpleLogger implements LoggerInterface
{
    public function log($level, $message, array $context = [])
    {
        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        echo "[{$date}] {$level}: {$message} {$contextStr}\n";
    }

    public function emergency($message, array $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
