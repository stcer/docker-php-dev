<?php

require 'vendor/autoload.php';

$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');

$producer = new RdKafka\Producer($conf);
$topic = $producer->newTopic("test_topic");

$topic->produce(RD_KAFKA_PARTITION_UA, 0, "Hello, Kafka!");
$producer->flush(1000);

echo "Message sent to Kafka\n";
