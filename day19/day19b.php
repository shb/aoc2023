<!--- Day 19: Aplenty --->
<?php include "day19.php";

// The map of nodes in the flow chart
$flow = new FlowChart();

$processing = false;
foreach (file("day19.in.txt") as $line) {
    // Immediatly trim the line, for good measure
    $line = trim($line);

    if (switch_to_processing($line)) {
        $flow->simplify();

        $combinations = restrict([
            'x' => [1,4000],
            'm' => [1,4000],
            'a' => [1,4000],
            's' => [1,4000]
        ], $flow->nodes['in']);

        echo "combinations = $combinations\n";

        continue;
    }

    if (!$processing) {
        $flow->addNode($line);
        continue;
    }
}

function process_part(string $desc): int {
    global $X, $M, $A, $S;

    $part = new Part($desc);
    
    $accept = ($X[$part->x] + $M[$part->m] + $A[$part->a] + $S[$part->s]) == 4;

    if ($accept) return $part->get_tot_rating();
    else return 0;
}

function restrict (array $intervals, Node $node): int {
    global $flow;
    global $X, $M, $A, $S;

    $combinations = 0;

    foreach ($node->rules as $rule) {
        $cat  = $rule[0];
        $sign = $rule[1];
        $val  = intval($rule[2]);
        $dest = $rule[3];

        echo "$node->name:\t".json_encode($intervals)."\t$cat $sign $val $dest\t";

        $sub_intervals = $intervals;

        switch ($sign) {
            case '<':
                $sub_intervals[$cat][1] = min($val-1, $intervals[$cat][1]);
                $intervals[$cat][0] = max($val, $intervals[$cat][0]);
                break;
            case '>':
                $sub_intervals[$cat][0] = max($val+1, $intervals[$cat][0]);
                $intervals[$cat][1] = min($val, $intervals[$cat][1]);
                break;
        }

        switch ($dest) {
            case 'A':
                echo json_encode($sub_intervals)."\n";
                $combinations += add_combination($sub_intervals);
                break;
            case 'R':
                echo json_encode($sub_intervals)."\n";
                // Actually, we don't care about this case
                break;
            default:
                echo "\n";
                $combinations += restrict($sub_intervals, $flow->nodes[$dest]);
        }
    }

    echo "$node->name:\t".json_encode($intervals)."\t$node->default\n";
    switch ($node->default) {
        case 'A':
            echo "\n";
            $combinations += add_combination($intervals);
            break;
        case 'R':
            echo "\n";
            // Actually, we don't care about this case
            break;
        default:
            $combinations += restrict($intervals, $flow->nodes[$node->default]);
    }

    return $combinations;
}

function add_combination (array $intervals): int {
    $X = []; $M = []; $A = []; $S = [];

    for ($x = $intervals['x'][0]; $x <= $intervals['x'][1]; $x++) $X[$x] = 1;
    for ($m = $intervals['m'][0]; $m <= $intervals['m'][1]; $m++) $M[$m] = 1;
    for ($a = $intervals['a'][0]; $a <= $intervals['a'][1]; $a++) $A[$a] = 1;
    for ($s = $intervals['s'][0]; $s <= $intervals['s'][1]; $s++) $S[$s] = 1;

    return (array_sum($X) * array_sum($M) * array_sum($A) * array_sum($S));
}


/* $A = 0;
for ($x = 1; $x <= 4000; $x++)
for ($m = 1; $m <= 4000; $m++) {
for ($a = 1; $a <= 4000; $a++)
for ($s = 1; $s <= 4000; $s++) {
    $line = "{x=$x,m=$m,a=$a,s=$s}";
    $part = new Part($line);
    #echo "$line: \t";
    if ($flow->process($part)) $A++;
}
    echo "tot = \t$A\n";
} */
