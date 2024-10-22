<?php

namespace App\Services;

class AhoCorasick {
    protected $trie = [];
    protected $failure = [];
    protected $output = [];

    public function __construct(array $patterns) {
        $this->buildTrie($patterns);
        $this->buildFailure();
    }

    protected function buildTrie(array $patterns) {
        $this->trie = [];
        foreach ($patterns as $index => $pattern) {
            $node = &$this->trie;
            foreach (str_split($pattern) as $char) {
                if (!isset($node[$char])) {
                    $node[$char] = [];
                }
                $node = &$node[$char];
            }
            $node['output'][] = $index;
        }
    }

    protected function buildFailure() {
        $this->failure = [];
        $this->output = [];
        $queue = [];
        foreach ($this->trie as $char => &$node) {
            $this->failure[$char] = &$this->trie;
            $queue[] = &$node;
        }

        while ($queue) {
            $current = array_shift($queue);
            foreach ($current as $char => &$next) {
                if ($char === 'output') continue;
                $queue[] = &$next;
                $fail = &$this->failure;
                while (!isset($fail[$char])) {
                    if ($fail === $this->trie) {
                        $fail = &$this->trie;
                        break;
                    }
                    $fail = &$this->failure[$fail];
                }
                $next['fail'] = &$fail[$char] ?? $this->trie;
                $next['output'] = array_merge($next['output'] ?? [], $next['fail']['output'] ?? []);
            }
        }
    }

    public function search($text) {
        $results = [];
        $node = &$this->trie;
        foreach (str_split($text) as $i => $char) {
            while (!isset($node[$char]) && $node !== $this->trie) {
                $node = &$this->failure[$node];
            }
            $node = &$node[$char] ?? $this->trie;
            if (!empty($node['output'])) {
                foreach ($node['output'] as $patternIndex) {
                    $results[] = [
                        'index' => $patternIndex,
                        'position' => $i
                    ];
                }
            }
        }
        return $results;
    }
}
