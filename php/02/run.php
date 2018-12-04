<?php

$file = file_get_contents(__DIR__ . '/input',false);
$lines = explode("\n", $file);
$t1 = 0;
$t2 = 0;
$sims = [];

foreach ($lines as $line) {
  list ($two, $three, $checksum, $splat) = parseLine($line);
  $sims[] = $splat;
}
$res = [];
foreach ($sims as $k=>$s) {
  foreach ($sims as $l=>$comp) {
    if ($k !== $l) {
      $diff = 0;
      for($i=0; $i<count($s); $i++) {
        if ($s[$i] !== $comp[$i]) {
          $diff++;
        }
      }
      if ($diff === 1) {
        $res[] = implode("", $s);
      }
    }
  }
}
var_dump($res);

function parseLine($line) {

  $letters = [];
  $splat = str_split($line);
  foreach ($splat as $letter) {
    $letters[$letter]++;
  }
  $two = array_filter($letters, function($i) {
    return $i===2;
  });
  $three = array_filter($letters, function($i) {
    return $i===3;
  });
  $checksum = 1;
  foreach ($letters as $l) {
    $checksum = $checksum * $l;
  }

  return [$two, $three, $checksum, $splat];
}
