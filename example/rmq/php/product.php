<?php

// 生产消息到默认exchange
// php example-product.php "test.abc" "{test data}"

use j\mq\log\SimpleLogger;
use j\mq\rmq\Producer;

require __DIR__ . "/vendor/autoload.php";

$routing_key = !empty($argv[1]) ? $argv[1] : 'test.info';
$data = implode(' ', array_slice($argv, 2));
if (empty($data)) {
    $data = "Hello World!";
}

$producer = Producer::getInstance();
$producer->setLogger(new SimpleLogger());
$producer->push($data, $routing_key);
