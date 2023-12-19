<!--- Day 4: Scratchcards --->
<?php

$lines = file('day04.in.txt');
$cards = array();
$stack = array();

$tot = 0;
foreach ($lines as $line) {
    preg_match('/^Card\s+(\d+):/', $line, $matches);
    $n = intval($matches[1]) - 1;
    $m = get_winning($line);

    $cards[$n] = $m;
    array_push($stack, $n);

    if ($m > 0) {
        $tot += pow(2, $m-1);
    }
}
echo "Tot = $tot\n";

$tot_cards = 0;
while (count($stack) > 0) {
    #echo join(",", $stack)."\n";
    $n = array_shift($stack);
    $tot_cards++;
    $w = array_key_exists($n, $cards) ? $cards[$n] : 0;

    #if ($w) echo "+ ";
    for ($i = 1; $i <= $w; $i++) {
        #echo ($n + $i)." ";
        array_push($stack, $n + $i);
    }
    #echo "\n";
    #if (count($stack) > 1000) break;
}
echo "Tot cards = $tot_cards\n";

function get_winning ($card) {
    $row = substr($card, 10);
    [$winning, $numbers] = explode("|", $row);
    $winning = preg_split('/\s+/', trim($winning));
    $numbers = trim($numbers);
    $winning_pattern = "/\b(?:".join("|", $winning).")\b/";
    return preg_match_all($winning_pattern, $numbers, $matches);
}