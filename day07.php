<!--- Day 7: Camel Cards ---
<?php

$types = [
    'card' => [],
    'two' => [],
    'double' => [],
    'three' => [],
    'full' => [],
    'four' => [],
    'five' => [],
];

$hands = file("day07.in.txt");

foreach ($hands as $hand) {
    [$cards, $bid] = explode(" ", trim($hand));
    echo "hand=$cards\tbid=$bid\n";
    $type = get_type($cards);
echo "type: $type\n";
    $types[$type][$cards] = $bid;
}

$tot = 0;
$rank = 1;
foreach($types as $type => $hands) {
    echo "\n$type:\n";
    uksort($hands, "sort_cards");
    foreach($hands as $cards => $bid) {
        echo "$cards = $bid x $rank\n";
        $tot += ($bid * ($rank++));
        echo "tot=$tot\n";
    }
}

echo "\ntot = $tot\n";


function get_type($cards) {
    $labels = array();
    for($c = 0; $c < 5; $c++) {
        $l = $cards[$c];
        if (array_key_exists($l, $labels)) $labels[$l] += 1;
        else $labels[$l] = 1;
    }
    arsort($labels, SORT_NUMERIC);
    $prev = '';
    foreach($labels as $label => $count) {
echo "$label => $count\n";
        switch($count) {
            case 5: return 'five';
            case 4: return 'four';
            case 3:
                $prev = 'three';
                continue 2;
            case 2:
                switch ($prev) {
                    case 'three': return 'full';
                    case 'two': return 'double';
                    default:
                        $prev = 'two';
                        continue 3;
                }
            default:
                if ($prev) return $prev;
                else return 'card';
        }
    }
}

function sort_cards($a, $b) {
    return translate($a) <=> translate($b);
}

function translate ($cards) {
    return str_replace(['A', 'K', 'Q', 'J', 'T'], ['F', 'E', 'D', 'C', 'B'], $cards);
}