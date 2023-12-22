<?php

function parse (string $path, callable $body): void {
    foreach(file($path) as $line) {
        $line = trim($line);
        $body($line);
    }
}
