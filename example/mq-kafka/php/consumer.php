<?php

require 'vendor/autoload.php';

$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');
//$conf->set('group.id', 'test_group');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(["test_topic"]);

echo "Waiting for messages...\n";

while (true) {
    $message = $consumer->consume(1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received: " . $message->payload . "\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            break;
        default:
            echo "Error: " . $message->errstr() . "\n";
    }
}
