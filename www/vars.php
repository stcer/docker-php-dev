<?php
# vars.php
/**
 * User: Administrator
 * Date: 2023/10/31
 * Time: 15:26
 */
echo "<pre>\n";

var_dump($_ENV);
var_dump($_SERVER);

echo "<hr />";
foreach ($_SERVER as $key => $value) {
    echo "{$key}: " . (getenv($key) == $value ? "True": "false");
    echo "\n";
}

echo "<hr />";
foreach ($_ENV as $key => $value) {
    echo "{$key}:" . (getenv($key) == $value ? "True": "false");
//    echo " " . getenv($key) . "::" . $value;
    echo "\n";
}
