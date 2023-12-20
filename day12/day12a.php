<!--- Day 12: Hot Springs --->
<?php

$valid = 0;
foreach (file("day12.in.txt") as $line) {
    [$pattern, $lengths] = explode(' ', trim($line));

    echo "\n$pattern $lengths\n";
    $candidates = get_candidates($pattern);
    $pattern = generate_pattern($lengths);
    $valid += count(array_filter($candidates, function ($cand) use ($pattern) {
        $v = preg_match($pattern, $cand);
        if ($v) echo "$cand\n";
        return $v;
    }));
    echo "valid = $valid\n";
}

function generate_pattern (string $groups): string {
    $lengths = explode(',', $groups);
    $hashes = array_map(function ($l) {
        return '\#{'.$l.'}';
    }, $lengths);
    $pattern = join("\\.+", $hashes);
    return '/^[^\#]*'.$pattern.'[^\#]*$/';
}

function get_candidates (string $pattern): array {
    // Get total number of '?'s
    $n = count(explode('?', $pattern)) - 1;

    #echo "$pattern\t$n\n";
    
    $candidates = [];

    foreach (generate_patches($n) as $patch) {
        $i = 0;
        $candidate = preg_replace_callback('/\?/', function () use ($patch, &$i) {
            return $patch[$i++];
        }, $pattern);
        #echo "$candidate\n";
        array_push($candidates, $candidate);
    }

    return $candidates;
}

function generate_patches (int $n): array {
    $patches = [];
    for ($i=0; $i < pow(2, $n); $i++) {
        $b = sprintf("%0{$n}b", $i);
        $p = str_replace(['0', '1'], ['.', '#'], $b);
        array_push($patches , $p);
    }
    return $patches;
}