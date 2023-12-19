<!--- Day 3: Gear Ratios --->
<?php

$columns = 140;
$rows = 140;

$lines = file('day03.in.txt');

$tot_parts = 0;
$parts = array();
$part_numbers = [];

/**
 * Add a part number to a part indexed by its position
 * in the parts chart.
 * 
 * The part is also identified by a part symbol.
 */
function add_part_number($r, $c, $symbol, $number) {
    global $parts;

    if (empty($parts["$r,$c"])) $parts["$r,$c"] = [
        'symbol' => $symbol,
        'numbers' => []
    ];

    array_push($parts["$r,$c"]['numbers'], $number);
}

function collect_parts ($number, $r, $s, $e) {
    global $columns;
    global $rows;
    global $lines;
    global $tot_parts;

    $col_start = max($s-1, 0);
    $col_end = min($columns, $e+1);
    $col_count = $col_end - $col_start;
    $row_start = max($r-1, 0);
    $row_end = min($rows, $r+2);

    $parts_found = 0;

    for ($i = $row_start; $i < $row_end; $i++)
        for ($j = $col_start; $j < $col_end; $j++) {
            $symbol = $lines[$i][$j];

            if (is_numeric($symbol)) continue;
            if ($symbol == '.') continue;

            add_part_number($i, $j, $symbol, $number);
            $parts_found ++;
        }

    if ($parts_found > 0) $tot_parts += intval($number);
}

function collect_part_number ($r, $s, $e) {
    global $columns;
    global $rows;
    global $lines;
    global $tot_parts;
    global $part_numbers;

    $number = substr($lines[$r], $s, $e - $s);
    $col_start = max(0, $s-1);
    $col_end = min($e+1, $columns);
    $col_count = $col_end - $col_start;
    
    $symbols = '';
    if ($r-1 > 0)     $symbols .= substr($lines[$r-1], $col_start, $col_count);
                      $symbols .= substr($lines[$r],   $col_start, $col_count);
    if ($r+1 < $rows) $symbols .= substr($lines[$r+1], $col_start, $col_count);
    $parts = preg_replace('/[0-9\.]+/', '', $symbols);
    
    echo "$number.$parts";

    #if (!empty($parts)) $tot_parts += intval($number);
}

$number = '';
$was_number = false;
$num_s = 0;
for($r = 0; $r < $rows; $r++){
    // Start row
    for($c = 0; $c < $columns; $c++) {
        // Read col in row:
        $char = $lines[$r][$c];
        $is_number = is_numeric($char);
        if ($is_number) {
            if (!$was_number) $num_s = $c;
            $number .= $char;
        } else {
            if ($was_number) {
                collect_parts(intval($number), $r, $num_s, $c);
            }
            $number = '';
        }
        // Store previous is_number
        $was_number = $is_number;
    }
    // Finish row
    if ($is_number) {
        collect_parts(intval($number), $r, $num_s, $c);
    }
    $number = '';
    $was_number = false;
}

echo "Parts = $tot_parts\n";

$tot_ratios = 0;
foreach ($parts as $coords => $part) {
    if ($part['symbol'] != "*") continue;
    if (count($part['numbers']) != 2) continue;

    $tot_ratios += $part['numbers'][0] * $part['numbers'][1];
}

echo "Ratios = $tot_ratios\n";

