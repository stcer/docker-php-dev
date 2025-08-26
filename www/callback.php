<?php


$callback = function(array $array) {
    return array_sum($array);
};

$makeClosure = function(callable $callback) {
    return $callback(...);
};

var_dump($callback);
$x = $makeClosure('array_sum');
var_dump($x);
echo $x([1,2,3]);

echo "\n";

$x = $makeClosure($callback);
var_dump($x);
echo $x([1,2,3]);



