<!--- Day 2: Cube Conundrum --->
<?php

function is_it_possible ($sample) {
    $bag = array(
        'red' => 12,
        'green' => 13,
        'blue' => 14
    );

    foreach($sample as $col => $qty) {
        if ($bag[$col] < $qty) return false;
    }

    return true;
}

function power ($set) {
    $pow = 1;
    foreach($set as $col => $qty) $pow *= $qty;
    return $pow; 
}

$lines = file('day02.in.txt');

$sum = 0;
$tot_pow = 0;
foreach ($lines as $line) {
    preg_match('/^Game\s+(\d+):\s+(.+)$/', trim($line), $matches);

    $game = $matches[1];
    $samples = $matches[2];

    $possible = true;
    $min = array('red' => 0, 'green' => 0, 'blue' => 0);

    echo "\ngame=$game:\n";
    foreach(explode(';', $samples) as $sample) {
        echo "\t";
        $s = array();
        $colors = explode(',', trim($sample));
        foreach ($colors as $color) {
            [$qty, $col] = explode(' ', trim($color));
            echo "color=$col, qty=$qty;\t";
            $min[$col] = max($min[$col], $qty);
            $s[$col] = $qty;
        }
        
        if(!is_it_possible($s)) {
            echo "IMPOSSIBLE";
            $possible = false;
        }
        
        echo "\n";
    }

    echo "min:\t";
    foreach($min as $col => $qty) {
        echo "color=$col, qty=$qty;\t";
    }
    echo "\n";

    if ($possible) $sum += $game;
    echo "tot=$sum\n";
    
    $pow = power($min);
    $tot_pow += $pow;
    echo "power=$pow\n";
    echo "tot_power=$tot_pow\n";
}

