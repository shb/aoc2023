<!--- Day 20: Pulse Propagation --->
<?php include 'common.php';

load('input.txt');

for ($i = 0; $i < 1000; $i++) {
    run();
    echo "pulses = $lows x $highs = ".($lows*$highs)."\n";
}
