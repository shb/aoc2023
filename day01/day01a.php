<!--- Day 1: Trebuchet?! --->
<?php

$lines = file("day01.in.txt");

$sum = 0;
foreach($lines as $line) {
    preg_match('/^\D*(\d)/', $line, $first);
    preg_match('/(\d)\D*$/', $line, $last);

    $num = intval($first[1].$last[1]);
    $sum += $num;

    echo "$line + $num = $sum\n";
}