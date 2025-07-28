<?php

$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka1:9092,kafka2:9092'); // 注意：Kafka 内部网络地址已更新
$producer = new RdKafka\Producer($conf);
$topic = $producer->newTopic("my_test_topic");

for ($i = 0; $i < 100; $i++) {
    $message = "Hello Kafka from PHP! (KRaft Mode) - Message " . $i . "," . date("Y-m-d H:i:s");
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message, "key-$i");
    echo "Sent: " . $message . PHP_EOL;
    $producer->poll(0);
//    sleep(1);
}

while ($producer->getOutQLen() > 0) {
    $producer->flush(1000);
}

echo "Messages sent successfully!" . PHP_EOL;

