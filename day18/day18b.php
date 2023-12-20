<!--- Day 18: Lavaduct Lagoon --->
<?php

$plan = file("day18.in.txt");

$map = [];
$min_x = 0;
$min_y = 0;
$max_x = 0;
$max_y = 0;

$xy = [0,0];
$area = 0;
$perimeter = 0;
foreach ($plan as $line) {
    $x1 = $xy[0];
    $y1 = $xy[1];

    preg_match('/^([DLRU])\s+(\d+)\s+\(\#([0-9a-f]{5})([0-9a-f])\)$/', $line, $matches);
    $dir = $matches[4];
    $length = intval($matches[3], 16);

    $perimeter += $length;
    move($dir, $length);

    $x2 = $xy[0];
    $y2 = $xy[1];

    $rect = ($y1 + $y2) * ($x1 - $x2) /2;
    $area += $rect;
    echo "($x1,$y1) -> ($x2,$y2)   \t: ($y1 + $y2) ($x1 - $x2) /2\t= $area\n";
}

echo "area = $area + $perimeter/2 + 1 = ".($area+$perimeter/2+1)."\n";

function move ($dir, $length) {
    global $xy;

    // 0 means R, 1 means D, 2 means L, and 3 means U
    switch ($dir) {
        case '0': case 'R': $xy[0] += $length; break;
        case '1': case 'D': $xy[1] += $length; break;
        case '2': case 'L': $xy[0] -= $length; break;
        case '3': case 'U': $xy[1] -= $length; break;
    }
}
