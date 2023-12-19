<!--- Day 10: Pipe Maze --->
<?php

$map = file("day10.in.txt");

$max = [140,140];  // Excluding
$start = [79,63];
$x = 0;
$y = -1;
$xy = [$start[0], $start[1]];
$pipes = array();

$l = 0;
do {
    $l++;
    $pipe = $map[$xy[1]][$xy[0]];
    $pipes[join(',',$xy)] = $pipe;
    echo $pipe." ";

    switch ($pipe) {
        case '7': $x -= 1; $y += 1; break;
        case 'F': $x += 1; $y += 1; break;
        case 'J': $x -= 1; $y -= 1; break;
        case 'L': $x += 1; $y -= 1; break;
        case '|': $x = 0; break;
        case '-': $y = 0; break;
    }
    $xy = [$xy[0]+$x, $xy[1]+$y];
    #echo join(",", $xy)."\n";
    
    #readline();
} while ($pipe != 'S');

echo "\nlength = ".($l/2)."\n";

$inside = 0;
$outside = 0;
$pipe = '';
$in = false;
for ($y = 0; $y < $max[1]; $y++) {
    for ($x = 0; $x < $max[0]; $x++) {
        $tile = $map[$y][$x];
        if (array_key_exists("$x,$y", $pipes)) {
            echo $tile;
            $pipe .= $tile;
            switch ($tile) {
                case '|':
                case 'S':
                    $in = !$in;
                    $pipe = '';
                    break;
                case '7':
                    if ($pipe[0] == 'L') {
                        $in = !$in;
                        $pipe = '';
                    } elseif ($pipe[0] == 'F')  {
                        $pipe = '';
                    }
                    break;
                case 'J':
                    if ($pipe[0] == 'F') {
                        $in = !$in;
                        $pipe = '';
                    } elseif ($pipe[0] == 'L')  {
                        $pipe = '';
                    }
                    break;
            }
            continue;
        }

        if ($in) {
            echo "I";
            $inside++;
        } else {
            echo "O";
            $outside++;
        }
    }
    echo "\n";
}

echo "inside = $inside\n";