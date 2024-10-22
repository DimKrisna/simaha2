<?php

namespace App\Libraries;
use AhoCorasick\MultiStringMatcher;

class AhoCorasickMatcher
{
    private $trie;
    private $output;
    private $fail;
    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
        $this->buildTrie();
    }

    private function buildTrie()
    {
        $this->trie = [];
        $this->output = [];
        $this->fail = [];

        $newState = 0;

        foreach ($this->patterns as $patternIndex => $pattern) {
            $currentState = 0;
            for ($i = 0; $i < strlen($pattern); $i++) {
                $symbol = $pattern[$i];
                if (!isset($this->trie[$currentState])) {
                    $this->trie[$currentState] = [];
                }
                if (!isset($this->trie[$currentState][$symbol])) {
                    $newState++;
                    $this->trie[$currentState][$symbol] = $newState;
                }
                $currentState = $this->trie[$currentState][$symbol];
            }
            if (!isset($this->output[$currentState])) {
                $this->output[$currentState] = [];
            }
            $this->output[$currentState][] = $patternIndex;
        }

        $queue = [];
        foreach ($this->trie[0] as $symbol => $state) {
            $this->fail[$state] = 0;
            $queue[] = $state;
        }

        while (!empty($queue)) {
            $r = array_shift($queue);
            if (!isset($this->trie[$r])) {
                continue;
            }
            foreach ($this->trie[$r] as $symbol => $u) {
                $queue[] = $u;
                $state = $this->fail[$r];
                while ($state > 0 && (!isset($this->trie[$state]) || !isset($this->trie[$state][$symbol]))) {
                    $state = $this->fail[$state];
                }
                if (isset($this->trie[$state][$symbol])) {
                    $this->fail[$u] = $this->trie[$state][$symbol];
                } else {
                    $this->fail[$u] = 0;
                }
                if (isset($this->output[$this->fail[$u]])) {
                    if (!isset($this->output[$u])) {
                        $this->output[$u] = [];
                    }
                    $this->output[$u] = array_merge($this->output[$u], $this->output[$this->fail[$u]]);
                }
            }
        }
    }

    public function searchIn($text)
    {
        $currentState = 0;
        $matches = [];

        for ($i = 0; $i < strlen($text); $i++) {
            $symbol = $text[$i];
            while ($currentState > 0 && (!isset($this->trie[$currentState]) || !isset($this->trie[$currentState][$symbol]))) {
                $currentState = $this->fail[$currentState];
            }
            if (isset($this->trie[$currentState][$symbol])) {
                $currentState = $this->trie[$currentState][$symbol];
            } else {
                $currentState = 0;
            }
            if (isset($this->output[$currentState])) {
                foreach ($this->output[$currentState] as $patternIndex) {
                    $matches[] = [
                        'index' => $patternIndex,
                        'position' => $i,
                    ];
                }
            }
        }

        return $matches;
    }
}
