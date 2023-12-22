<?php

define("GRID_WRAP_NONE", 0);
define("GRID_WRAP_TOROID", 1);
define("GRID_WRAP_TILE", 2);

/**
 * Get (defined) pairs of coordinates around a point in a grid
 */
function get_neighbors_around (array $grid, array $pos): array {
    $neighbors = [];

    for ($y = $pos[1]-1; $y <= $pos[1]+1; $y++)
    for ($x = $pos[0]-1; $x <= $pos[0]+1; $x++)
    if (($x != $pos[0] or $y != $pos[1]) and isset($grid[$y][$x]))
        $neighbors []= [$x, $y];

    return $neighbors;
}

/**
 * Get (defined) pairs of coordinates aside a point in a grid
 */
function get_neighbors_across (array $grid, array $pos, int $wrap = GRID_WRAP_NONE): array {
    $neighbors = [];

    $rows = count($grid);
    $columns = count($grid[0]);

    for ($y = $pos[1]-1; $y <= $pos[1]+1; $y++)
    for ($x = $pos[0]-1; $x <= $pos[0]+1; $x++)
    switch ($wrap) {
        case GRID_WRAP_TILE:
            if ($x != $pos[0] xor $y != $pos[1])
                $neighbors []= [$x, $y];
            break;
        case GRID_WRAP_TOROID:
            if ($x != $pos[0] xor $y != $pos[1])
                $neighbors []= [($columns+$x) % $columns, ($rows+$y) % $rows];
            break;
        case GRID_WRAP_NONE:
            if (($x == $pos[0] xor $y == $pos[1]) and isset($grid[$y][$x]))
                $neighbors []= [$x, $y];
            break;
    }

    return $neighbors;
}