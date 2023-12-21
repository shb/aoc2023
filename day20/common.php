<?php

$lows = 0;
$highs = 0;
$low_rxs = 0;

$modules = array();
$signals = [
    ['button', 0, 'broadcaster']
];

function load($file): void {
    global $modules;

    // Add and wire modules
    foreach (file("input.txt") as $line) {
        [$module, $outputs] = explode(' -> ', trim($line));

        echo "$module -> $outputs\n";

        if ($module == 'broadcaster') {
            $broadcaster = new Broadcaster('broadcaster', $outputs);
            continue;
        }

        if ($module[0] == '%') {
            $name = substr($module, 1);
            $flipflop = new FlipFlop($name, $outputs);
            continue;
        }

        if ($module[0] == '&') {
            $name = substr($module, 1);
            $and = new Conjunction($name, $outputs);
            continue;
        }
    }

    // Connect remaining dummy (sink) modules
    foreach ($modules as $name => $module)
    foreach ($module->outputs as $mod)
    if (empty($modules[$mod]))
        new Module($mod);

    echo "\n";
}

function run () {
    global $modules, $signals;
    global $lows, $highs, $low_rxs;

    $signals = [
        ['button', 0, 'broadcaster']
    ];

    $low_rxs = 0;

    while (count($signals)) {
        $signal = array_shift($signals);

        #echo "$signal[0] -$signal[1]-> $signal[2]:\n";

        switch ($signal[1]) {
            case 0: $lows++; break;
            case 1: $highs++; break;
        }

        if ($signal[2] === 'rx' && $signal[1] === 0)
            $low_rxs++;

        $modules[$signal[2]]->input($signal[0], $signal[1]);
    }
}

function send (string $src, int $pulse, string $dst): void {
    global $signals;

    #echo "\t$src -$pulse-> $dst\n";

    $signals []= [$src, $pulse, $dst];
}

class Module {
    var $name = 'none';

    public $inputs = array();
    public $outputs = [];

    function __construct (string $name, string $outputs = '') {
        global $modules;

        $this->name = $name;

        $modules[$this->name] = $this;

        $this->connect_out($outputs);

        // Find hanging connections
        foreach ($modules as $src)
        foreach ($src->outputs as $dst)
        if ($dst == $this->name)
            $this->connect_in($src->name);
    }

    public function connect_in (string $src): void {
        #echo "\t$this->name in: $src\n";
        $this->inputs[$src] = 0;
    }

    protected function connect_out (string $dest): void {
        global $modules;

        $this->outputs = preg_split('/\s*,\s*/', $dest);

        // Try to connect to destination modules
        foreach ($this->outputs as $out) {
            if (isset($modules[$out])) $modules[$out]->connect_in($this->name);
        }

        #echo "\t$this->name out: ".json_encode($this->outputs)."\n";
    }

    protected function send (int $pulse): void {
        foreach ($this->outputs as $dst) {
            send($this->name, $pulse, $dst);
        }
    }

    function input (string $src, int $pulse): void {
        // The base Module acts as a sink for inputs
    }
}

class Broadcaster extends Module {
    var $name = 'broadcaster';

    function input (string $src, int $pulse): void {
        $this->send($pulse);
    }
}

class FlipFlop extends Module {
    private bool $state = false;

    function input ($src, $pulse): void {
        if ($pulse == 1) return;

        if ($pulse == 0) $this->state = !$this->state;

        $this->send($this->state? 1 : 0);
    }
}

class Conjunction extends Module {
    function input ($src, $pulse): void {
        $this->inputs[$src] = $pulse;

        #echo "\t".json_encode($this->inputs)."\n";

        $sum = array_sum(array_values($this->inputs));

        if ($sum == count($this->inputs)) $this->send(0);
        else $this->send(1);
    }
}