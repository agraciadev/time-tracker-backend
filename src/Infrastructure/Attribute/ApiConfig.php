<?php

namespace App\Infrastructure\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class ApiConfig
{

    public function __construct(
        private bool $secure = false,
        private array $serializerGroups = []
    )
    {
    }

    public function secure(): bool
    {
        return $this->secure;
    }

    public function serializerGroups(): array
    {
        return $this->serializerGroups;
    }
}
