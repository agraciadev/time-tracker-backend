<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepository;

class InMemoryTaskRepository implements TaskRepository
{
    protected $tasks = [];

    /**
     * @param Task $task
     */
    public function save(Task $task): void
    {
        $this->tasks[] = $task;
    }

    /**
     * @param string $name
     * @return Task|null
     */
    public function byName(string $name): ?Task
    {
        $task = current(array_filter($this->tasks, function ($task) use ($name) {
            return $task->name() == $name;
        }));

        return $task ?: null;
    }

    public function all(): array
    {
        return $this->tasks;
    }

    public function inProgress(): ?Task
    {
        $task = current(array_filter($this->tasks, function ($task) {
            return $task->inProgress();
        }));

        return $task ?: null;
    }
}
