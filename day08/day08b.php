<!--- Day 8: Haunted Wasteland --->
<?php

require __DIR__.'/day08.php';

$lines = file("day08.in.txt");

$instructions = null;
$graphs = null;

foreach($lines as $line) {
    if (!$instructions) {
        $instructions = new Instructions($line);
        continue;
    }

    if (empty(trim($line))) {
        $graphs = new Graphs();
        continue;
    }

    $graphs->addNode($line);
}

$i = 0;
while (!$graphs->finished()) {
    $i++;
    $graphs->go($instructions->next());
}
echo "\nsteps: $i\n";

class Graphs {
    private $backlog = [];
    private $graphs = array();

    function addNode ($desc) {
        $this->maybeAddGraph($desc);

        foreach($this->graphs as $start => $graph)
            $graph->addNode($desc);

        array_push($this->backlog, $desc);
    }

    function go ($dir) {
        foreach($this->graphs as $start => $graph)
            $graph->go($dir);
    }

    function finished () {
        $finished = 0;
        foreach($this->graphs as $start => $graph)
            if ($graph->finished()) $finished++;
        if ($finished) {
            echo "$finished/".count($this->graphs);
            foreach($this->graphs as $start => $graph)
                echo "\t$graph->current";
            echo "\n";
            foreach($this->graphs as $start => $graph)
                echo "\t$graph->steps";
            echo "\n";
            readline();
        }
        return $finished === count($this->graphs);
    }

    private function maybeAddGraph ($desc) {
        [$node, $children] = explode(" = ", trim($desc));
        if ($node[2] === 'A') {
            $this->graphs[$node] = new Graph($node, $this->backlog);
            foreach ($this->graphs as $start => $grash)
                echo "\t$start";
            echo "\n";
        }
    }
}