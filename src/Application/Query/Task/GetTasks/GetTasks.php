<?php declare(strict_types=1);

namespace App\Application\Query\Task\GetTasks;

use App\Domain\Repository\TaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetTasks
{
    private TaskRepository $taskRepository;

    public function __construct(
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param GetTasksCommand $command
     * @return array
     */
    public function __invoke(GetTasksCommand $command): array
    {
        return $this->taskRepository->all();
    }
}