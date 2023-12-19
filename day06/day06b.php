<!--- Day 6: Wait For It --->
<?php

$input = file("day06.in.txt");

$time  = join('', preg_split('/\s+/',trim(explode(":", $input[0])[1])));
$space = join('', preg_split('/\s+/',trim(explode(":", $input[1])[1])));

$margin = 1;

    $t = $time;
    $R = $space;
    echo "time=$t\tdistance=$R\n";
    $count = 0;
    for ($x = 1; $x < $t; $x++) {
        if (($x * $t - pow($x, 2)) > $R) {
            #echo "($t-$x) * $x = ".(($t-$x) * $x)." wins $R\n";
            $count++;
        } else {
            #echo "($t-$x) * $x = ".(($t-$x) * $x)." loses $R\n";
        }
    }
    $wins = $count;
    echo "Wins: $wins\n";
    $margin *= $wins;

echo "Margin: $margin\n";

function compute_wins ($n) {
    return ($n * 2) - ($n % 2);
}