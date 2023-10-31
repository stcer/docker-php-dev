<?php

function checkTcpServer($host, $port)
{
    $fd = stream_socket_client("{$host}:{$port}", $errno, $errstr, 1);
    if ($fd && !$errno) {
        fclose($fd);
        return true;
    } else {
        return false;
    }
}

$servers = [
    ['redis', 6379],
    ['db', 3306],
    ['rmq', 5672],
];

foreach($servers as $server) {
    [$host, $port] = $server;
    $rs = checkTcpServer($host, $port);
    if ($rs) {
        echo "server {$host} ok <br />\n";
    }
}

echo gethostbyname('cdshop');
