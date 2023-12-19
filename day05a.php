<!--- Day 5: If You Give A Seed A Fertilizer --->
<?php

$lines = file("day05.in.txt");
$seeds = array();
$maps = array();

$map = '';
foreach($lines as $line) {
    if ($map && !empty(trim($line))) {
        $maps[$map]->addMapping(trim($line));
        continue;
    }

    if (preg_match('/^seeds\s*:\s+([0-9 ]+)$/', $line, $matches)) {
        $seeds = array_map("intval", explode(" ", $matches[1]));
        continue;
    }

    if (preg_match('/^(\S+)\s+map\s*:\s*$/', $line, $matches)) {
        $map = $matches[1];
        $maps[$map] = new Map($map);
        continue;
    }

    $map = '';
    continue;
}

$lowest = PHP_INT_MAX;
foreach ($seeds as $seed) {
    echo "\nseed: $seed\n";
    $value = $seed;
    foreach(array_values($maps) as $map)
        $value = $map->map($value);
    $lowest = min ($value, $lowest);
}
echo "\nLowest location #: $lowest\n";

class Map {
    private $name;
    private $ranges = array();

    function __construct ($name) {
        $this->name = $name;
    }

    function addMapping ($def) {
        array_push($this->ranges, array_map("intval", explode(" ", trim($def))));
    }

    function map ($value) {
        $mapped = $value;
        foreach ($this->ranges as $range) {
            if ($value < $range[1]) continue;
            if ($value > $range[1] + $range[2]) continue;
            $mapped = $value - $range[1] + $range[0];
            break;
        }
        echo "$this->name: $value -> $mapped\n";
        return $mapped;
    }
}