<!--- Day 6: Wait For It --->
<?php

$input = file("day06.in.txt");

$times  = preg_split('/\s+/',trim(explode(":", $input[0])[1]));
$spaces = preg_split('/\s+/',trim(explode(":", $input[1])[1]));

$races = array_map(function ($time, $space) {
    return [
        'time' => $time,
        'space' => $space
    ];
}, $times, $spaces);

$margin = 1;
foreach ($races as $race) {
    $t = $race['time'];
    $R = $race['space'];
    echo "time=$t\tdistance=$R\n";
    $count = 0;
    for ($x = 1; $x <= $t; $x++) {
        if (($x * $t - pow($x, 2)) > $R) {
            echo "($t-$x) * $x = ".(($t-$x) * $x)." wins $R\n";
            $count++;
        } else {
            echo "($t-$x) * $x = ".(($t-$x) * $x)." loses $R\n";
        }
    }
    $wins = /* compute_wins( */$count/* ) */;
    echo "Wins: $wins\n";
    $margin *= $wins;
}
echo "Margin: $margin\n";

function compute_wins ($n) {
    return ($n % 2)
        ? ($n-1) * 2 +1
        : $n * 2;
}