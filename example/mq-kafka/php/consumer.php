<?php

require 'vendor/autoload.php';

$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');
$conf->set('group.id', 'test_group4');
$conf->set('enable.auto.commit', 'true');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(["test_topic4"]);

echo "Waiting for messages...\n";
while (true) {
    $message = $consumer->consume(1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received: " . $message->payload . "\n";
            echo "          " . date('Y-m-d H:i:s') . "\n";
            sleep(1);
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "Timed out\n";
            break;
        default:
            echo "Error: " . $message->errstr() . "\n";
    }
}
