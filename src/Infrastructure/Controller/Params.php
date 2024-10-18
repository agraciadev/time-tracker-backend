<?php

namespace App\Infrastructure\Controller;

class Params
{

    public function __construct(
        private array $params)
    {
    }

    public function get($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } elseif (str_contains($name, '.')) {
            $loc = &$this->params;
            foreach (explode('.', $name) as $part) {
                $loc = &$loc[$part];
            }
            return $loc ?? null;
        }
        return null;
    }

    public function getAll(): array
    {
        return $this->params;
    }
}
