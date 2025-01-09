<?php
// cli������
// ���ö���
// php receive.php test test.info test.add

// ��ʱ����, �ͻ��������жϺ󣬶����Զ�ɾ��
// php receive.php "" test.info test.del

use j\mq\log\SimpleLogger;
use j\mq\rmq\Receiver;

require_once __DIR__ . '/vendor/autoload.php';

$queueName = $argv[1] ?? '';
$keys = array_slice($argv, 2);
if (empty($queueName) && empty($keys)) {
    file_put_contents('php://stderr', "Usage: $argv[0] queueName [binding_key]\n");
    exit(1);
}

$receiver = new Receiver();
$receiver->setLogger(new SimpleLogger());
$receiver->receive($queueName, $keys, function ($msg) {
    echo " [x] Received ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo " [x] Done {$msg->delivery_info['delivery_tag']}", "\n";

    // act �ظ�ȷ����Ϣ
    return true;
});
