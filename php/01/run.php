#!/bin/php
<?php

$file = file_get_contents(__DIR__ . '/input',false);
$lines = explode("\n", $file);
$result = 0;
foreach ($lines as $line) {
    $result = parseAndCount($result, $line);
}

function parseAndCount($a, $inputLine) {
    $n = (int)$inputLine;
    return $a+$n;
}

echo $result . PHP_EOL;