<?php

$file = file_get_contents(__DIR__ . '/../input',false);
$lines = explode("\n", $file);
$result = 0;
$hits = [(string)$result => 0];
while (!$firstTwiceReached) {
  foreach ($lines as $line) {
      $result = parseAndCount($result, $line);
      $k = (string)$result;
      if (array_key_exists($k, $hits)) {
        echo "FOUND";
        break(2);
      }
      $hits[$k] = $hits[$k]++;
  }
}

echo "First hit reached twice: " . $result . PHP_EOL;

function parseAndCount($a, $inputLine) {
    $n = (int)$inputLine;
    return $a+$n;
}
