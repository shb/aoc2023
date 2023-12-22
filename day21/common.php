<?php

require_once '../lib/file.php';
require_once '../lib/grid.php';

$map = [];

$r = 0;
$x = 0;
$y = 0;

parse($input, function (string $line) {
    global $map;
    global $r, $x, $y;

    $row = str_split($line);
    if (strpos($line, 'S') !== false) {
        $x = strpos($line, 'S');
        $y = $r;
    }

    $map []= str_split($line);

    $r++;
});

$visited_even = array();
$visited_odd  = array();
$even = 0;
$odd  = 0;
$points = array(
    "$x,$y" => [$x,$y]
);

$rows = count($map);
$columns = count($map[0]);

for ($s = 0; $s < $steps; $s++) {
    $new_points = array();

    foreach ($points as $key => $point) {
        $nighs = get_neighbors_across($map, $point, $wrap);

        foreach ($nighs as $nigh) {
            $spot = ($wrap === GRID_WRAP_TILE)
                ? $map[($rows + ($nigh[1] % $rows)) % $rows][($columns + ($nigh[0] % $columns)) % $columns]
                : $map[$nigh[1]][$nigh[0]];

            if ($spot == '.' or $spot == 'S') {
                if (isset($visited_even[join(',', $nigh)])) continue;
                if (isset($visited_odd [join(',', $nigh)])) continue;
                $new_points[join(',', $nigh)] = $nigh;
            }
        }
    }

    if ($s % 2) {
        $even += count($visited_even);
        echo "points(e) = $even \n";
        $visited_odd  = $points;
    } else {
        $odd  += count($visited_odd);
        echo "points(o) = $odd\n";
        $visited_even = $points;
    }

    $points = $new_points;
}

echo "points = ".count($points)."\n";