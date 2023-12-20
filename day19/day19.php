<?php

function switch_to_processing (string $line): bool {
    global $processing;

    if (empty($line))
        return $processing = true;
    else
        return false;
}

class Flowchart {
    var $nodes = array();

    private $aliases = array();

    function addNode(string $line): void {
        $node = new Node($line);
        $this->nodes[$node->name] = $node;
        if (count($node->rules) == 0) {
            $this->aliases[$node->name] = $node->default;
            $this->simplify();
        }
    }

    function process(Part $part): bool {
        $dest = 'in';
        for (
            $dest = 'in';
            $dest != 'A' && $dest != 'R';
            $dest = $rule->process($part)
        ) {
            $rule = $this->nodes[$dest];
            #echo "$dest -> ";
        }
        #echo "$dest\n";

        if ($dest == 'A') return true;
        else return false;
    }

    function simplify (): void {
        for (
            $ca = 0;
            count($this->aliases) > $ca;
            $ca = count($this->aliases)
        )
            foreach ($this->aliases as $src => $dest)
                $this->replace($src, $dest);
    }

    private function replace ($label, $dest): void {
        $prev = count($this->aliases);

        foreach ($this->nodes as $name => $node) {
            $node->replace($label, $dest);
            if (count($node->rules) == 0) {
                $this->aliases[$node->name] = $node->default;
                unset($this->nodes[$name]);
            }
        }
    }
}

class Node {
    var $name = '';
    var $rules = [];
    var $default = 'E';

    function __construct(string $desc) {
        $this->parse_name_and_rules($desc);
    }

    /**
     * Process a Part and return a destination name
     */
    function process (Part $part): string {
        $dest = '';

        for (
            // Starting from rule #0...
            $i = 0;
            // ...while we have no dest and we still have rules...
            empty($dest) and $i < count($this->rules);
            // ...apply the rule to the part (and advance rules index)
            $dest = $this->apply($this->rules[$i++], $part)
        );

        if (empty($dest)) return $this->default;
        else return $dest;
    }

    function replace ($label, $dest): void {
        // Substitute destination inside rules
        foreach ($this->rules as &$rule)
            if ($rule[3] == $label) {
                #echo "$this->name: replace $label -> $dest\n";
                $rule[3] = $dest;
            }

        // Substitute default destination
        if ($this->default == $label) {
            #echo "$this->name: replace $label -> $dest\n";
            $this->default = $dest;
        }

        $this->prune_rules();
    }

    private function parse_name_and_rules (string $desc): void {
        preg_match('/^([a-z]+)\{(.+)\}$/', $desc, $matches);
        $this->name = $matches[1];
        $this->parse_rules($matches[2]);
    }

    private function parse_rules (string $rules): void {
        foreach (explode(',', $rules) as $rule) {
            $r = preg_match('/^([xmas])([<>])(\d+):([ARa-z]+)$/', $rule, $matches);
            if ($r) {
                #echo "Rule: if $matches[1] $matches[2] $matches[3] then go to $matches[4]\n";
                array_push($this->rules, array_slice($matches, 1));
            } else {
                #echo "Default: go to $rule\n";
                $this->default = $rule;
            }
        }

        $this->prune_rules();
    }

    /**
     * Remove redudant rules, e.g. those that don't give
     * a destination different than the default.
     */
    private function prune_rules (): void {
        do {
            $rule = array_pop($this->rules);

            if (empty($rule)) break;

            if ($rule[3] !== $this->default) {
                array_push($this->rules, $rule);
            } else {
                #echo "$this->name: pruning ".join(' ',$rule).",\t$this->default\n";
            }
        } while ($rule[3] === $this->default);
    }

    /**
     * Compute min and max rating for possible acceptance
     */
    private function compute_limits (): void {

    }

    private function apply (array $rule, Part $part): string {
        $val  = $part->{$rule[0]};
        $sign        = $rule[1];
        $thresh      = $rule[2];
        $dest        = $rule[3];

        if ($this->eval($val, $sign, $thresh))
            return $dest;
        else
            return '';
    }

    private function eval ($val, $sign, $thresh): bool {
        switch ($sign) {
            case '<': return ($val < $thresh);
            case '>': return ($val > $thresh);
        }
    }
}

class Part {
    var $x = 0;
    var $m = 0;
    var $a = 0;
    var $s = 0;

    function __construct($desc) {
        // Strip the braces
        $desc = trim($desc, '{}');
        // Split the $desc string on ','...
        $rating = explode(',', $desc);
        foreach ($rating as $rating) {
            // ...and '='...
            [$p, $v] = explode('=', $rating);
            // ...to get each pair of category and rating value 
            $this->$p = $v;
        }
    }

    function get_tot_rating (): int {
        return $this->x + $this->m + $this->a + $this->s;
    }
}