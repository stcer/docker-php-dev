<?php

$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka1:9092,kafka2:9092'); // 注意：Kafka 内部网络地址已更新
$conf->set('group.id', 'my_consumer_group');
$conf->set('auto.offset.reset', 'earliest');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['my_test_topic']);

echo "Waiting for messages... (Press Ctrl+C to exit)" . PHP_EOL;

while (true) {
    $message = $consumer->consume(120*1000);

    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received message: " . $message->payload . " (from partition " . $message->partition . ")" . PHP_EOL;
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more..." . PHP_EOL;
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out waiting for messages..." . PHP_EOL;
            break;
        default:
            echo "Error: " . $message->errstr() . PHP_EOL;
            break;
    }
}
