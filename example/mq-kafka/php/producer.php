<?php

require 'vendor/autoload.php';

$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');

$producer = new RdKafka\Producer($conf);

//$topicConf = new RdKafka\TopicConf();
//$topicConf->setPartitioner(2);
$topic = $producer->newTopic("test_topic4");

foreach (range(0, $argv[1] ?? 10) as $i) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Hello, Kafka{$i}! " . time());
}
$producer->flush(1000);

echo "Message sent to Kafka\n";
