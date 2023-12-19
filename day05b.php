<!--- Day 5: If You Give A Seed A Fertilizer --->
<?php

$lines = file("day05.in.txt");
$maps = array();

$map = '';
foreach($lines as $line) {
    if ($map && !empty(trim($line))) {
        $maps[$map]->addMapping(trim($line));
        continue;
    }

    if (preg_match('/^seeds\s*:\s+([0-9 ]+)$/', $line, $matches)) {
        $seeds = new Seeds($matches[1]);
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

for ($location = 0; ; $location++) {
    $value = $location;
    echo "\nlocation: $location\n";
    foreach(array_reverse(array_values($maps)) as $map)
        $value = $map->map($value);
    if ($seeds->exists($value)) break;
}
echo "\nLowest location #: $location\n";

function map_seeds ($list) {
    global $seeds;

    $ranges = array_map("intval", explode(" ", $list));

    while(count($ranges)) {
        $start = array_shift($ranges);
        $count = array_shift($ranges);
        array_push($seeds, [$start, $count]);
    }
}

class Seeds {
    private $ranges = array();

    function __construct ($list) {
        $ranges = array_map("intval", explode(" ", $list));

        while(count($ranges)) {
            $start = array_shift($ranges);
            $count = array_shift($ranges);
            array_push($this->ranges, [$start, $count]);
        }
    }

    function exists ($seed) {
        foreach($this->ranges as $range) {
            if ($seed >= $range[0] && $seed < ($range[0] + $range[1])) {
                echo "seed $seed in range ".join("+", $range)."\n";
                return true;
            }
        }
        return false;
    }
}

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
            if ($value < $range[0]) continue;
            if ($value >= $range[0] + $range[2]) continue;
            $mapped = $value - $range[0] + $range[1];
            break;
        }
        #echo "$this->name: $mapped <- $value\n";
        return $mapped;
    }
}