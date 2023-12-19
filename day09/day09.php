<!--- Day 9: Mirage Maintenance --->
<?php

$lines = file("day09.in.txt");

$tot_prev = 0;
$tot_next = 0;
foreach ($lines as $line) {
    $series = explode(" ", trim($line));

    [$prev, $next] = forecast($series);
    $tot_prev += $prev;
    $tot_next += $next;
    echo "prev = $prev\ttot = $tot_prev\n";
    echo "next = $next\ttot = $tot_next\n";
    #readline();
}

function forecast ($series, $firsts = [], $lasts = []) {
    echo join(" ", $series)."\n";

    $err = 0;
    $diffs = [];
    for($i = 0; $i < count($series) - 1; $i++) {
        $diff = $series[$i+1] - $series[$i];
        array_push($diffs, $diff);
        if ($diff !== 0) $err = $diff;
    }

    array_push($firsts, (count($firsts) % 2) ? -$series[0] : +$series[0]);
    array_push($lasts, $series[$i]);

    if ($err != 0) return forecast($diffs, $firsts, $lasts);
    else return [
        array_sum($firsts),
        array_sum($lasts)
    ];
}
