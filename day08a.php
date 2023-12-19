<!--- Day 8: Haunted Wasteland --->
<?php

require __DIR__.'/day08.php';

$lines = file("day08.in.txt");

$instructions = null;
$graph = null;

foreach($lines as $line) {
    if (empty(trim($line))) {
        $graph = new Graph('AAA');
        continue;
    }

    if (!$instructions) {
        $instructions = new Instructions($line);
        continue;
    }

    $graph->addNode($line);
}

echo "\n";

$i = 0;
while (!$graph->finished()) {
    $graph->go($instructions->next());
    $i ++;
}
echo "\nsteps: $i\n";
