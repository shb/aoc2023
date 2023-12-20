<!--- Day 19: Aplenty --->
<?php include "day19.php";

// The map of nodes in the flow chart
$flow = new FlowChart();

// Tells whether we are parsing flow descroption
// or processing parts
$processing = false;

$tot = 0;
foreach (file("day19.in.txt") as $line) {
    // Immediatly trim the line, for good measure
    $line = trim($line);

    if (switch_to_processing($line)) {
        $flow->simplify();
        continue;
    }

    if ($processing) {
        $tot += process_part($line);
        echo "tot = $tot\n";
    } else {
        $flow->addNode($line);
    }
}

function process_part (string $line): int {
    global $flow;

    $part = new Part($line);
    if ($flow->process($part)) {
        return $part->get_tot_rating();
    } else {
        return 0;
    }
}
