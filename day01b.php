<!--- Day 1: Trebuchet?! --->
<?php

$numbers = array(
    '1' => 1,
    '2' => 2,
    '3' => 3,
    '4' => 4,
    '5' => 5,
    '6' => 6,
    '7' => 7,
    '8' => 8,
    '9' => 9,
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9
);
$digits = join('|', array_keys($numbers));
$digits_rev = strrev($digits);

$lines = file("day01.in.txt");

$sum = 0;
foreach($lines as $line) {
    preg_match('/^.*('.$digits    .')/U',        trim($line) , $first);
    preg_match('/^.*('.$digits_rev.')/U', strrev(trim($line)), $last );

    $num = intval($numbers[$first[1]].$numbers[strrev($last[1])]);
    $sum += $num;

    echo "$line + $num = $sum\n";
}
