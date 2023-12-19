<!--- Day 11: Cosmic Expansion --->
<?php

$space = file("day11.in.txt");

define("LAMBDA", 1000000);

$map = [];

$x = 0;
$y = 0;
foreach ($space as $lat) {
    for ($x=0; $x<strlen(trim($lat)); $x++) {
        $lon = $lat[$x];
        $map[$y][$x] = $lon;
    }
    $y++;
}

$max_x = $x;
$max_y = $y;

$c_x = [];
$d_x = [];
$D_x = 0;
for ($x = 0; $x < $max_x; $x++) {
    echo"x=$x\t";
    // Count galaxies at x
    $c_x[$x] = 0;
    for ($y=0; $y < $max_y; $y++)
        if ($map[$y][$x] == '#') $c_x[$x]++;
    echo "c=$c_x[$x]\t";

    // Increment distance of visited galaxies
    foreach ($d_x as $i => &$d) {
        $inc = $c_x[$i];
        $d += $inc * (($c_x[$x] == 0)? LAMBDA : 1);
    }
    echo join(",", $d_x)."\n";

    // Add distance of current galaxies
    $d_x[$x] = 0;

    // Accrue distance
    $D_x += array_sum($d_x) * $c_x[$x];
}
echo "Dx = $D_x\n";

$c_y = [];
$d_y = [];
$D_y = 0;
for ($y = 0; $y < $max_y; $y++) {
    echo"x=$y\t";
    // Count galaxies at x
    $c_y[$y] = 0;
    for ($x=0; $x < $max_x; $x++)
        if ($map[$y][$x] == '#') $c_y[$y]++;
    echo "c=$c_y[$y]\t";

    // Increment distance of visited galaxies
    foreach ($d_y as $i => &$d) {
        $inc = $c_y[$i];
        $d += $inc * (($c_y[$y] == 0)? LAMBDA : 1);
    }
    echo join(",", $d_y)."\n";

    // Add distance of current galaxies
    $d_y[$y] = 0;

    // Accrue distance
    $D_y += array_sum($d_y) * $c_y[$y];
}
echo "Dy = $D_y\n";

echo "tot = ".($D_x+$D_y)."\n";