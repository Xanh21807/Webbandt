<?php
require __DIR__ . '/../vendor/autoload.php';
$tests = ["8GB", "5-10 triệu", "pin trâu", "điện thoại giá rẻ"];
$out = [];
foreach ($tests as $t) {
    $out[$t] = \App\Services\SearchParser::parse($t);
}
echo json_encode($out, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . PHP_EOL;
