<?php declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\Entity\Task;

interface TaskRepository
{
    public function save(Task $task): void;

    public function byName(string $name): ?Task;

    public function all(): array;

    public function inProgress(): ?Task;
}