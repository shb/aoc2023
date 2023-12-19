<?php

class Instructions {
    private $instructions = '';
    private $i = 0;
    private $length = 0;

    function __construct ($instructions) {
        $this->instructions = trim($instructions);
        $this->length = strlen($this->instructions);
    }

    function next () {
        return $this->instructions[$this->i++ % $this->length];
    }
}

class Graph {
    public $current = '';
    public $steps = null;

    private $count = 0;
    private $nodes = array();
    private $start = 'AAA';

    function __construct($start = 'AAA', $backlog = []) {
        $this->start = $start;
        $this->current = $start;
        foreach($backlog as $line)
            $this->addNode($line);
    }

    function addNode ($desc) {
        [$n, $children] = explode(" = ", trim($desc));
        $this->nodes[$n] = explode(', ', trim($children, '()'));
    }

    function go($dir) {
        switch($dir) {
            case 'L':
                $this->current = $this->nodes[$this->current][0];
                break;
            case 'R':
                $this->current = $this->nodes[$this->current][1];
                break;
        }
        $this->count++;
    }

    function finished () {
        $finished = $this->current[2] === 'Z';
        if ($finished) {
            $this->steps = $this->count;
            $this->count = 0;
        }
        return $finished;
    }
}