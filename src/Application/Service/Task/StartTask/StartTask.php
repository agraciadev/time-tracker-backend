<?php declare(strict_types=1);

namespace App\Application\Service\Task\StartTask;

use App\Domain\Entity\Task;
use App\Domain\Exception\Task\TaskInProgressException;
use App\Domain\Repository\TaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class StartTask
{
    private TaskRepository $taskRepository;

    public function __construct(
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param StartTaskCommand $command
     * @throws TaskInProgressException
     */
    public function __invoke(StartTaskCommand $command): void
    {
        // Check if there is a task already in progress
        if ($this->taskRepository->inProgress()) {
            throw new TaskInProgressException();
        }

        $task = $this->taskRepository->byName(name: $command->name());

        if (empty($task)) {
            $task = Task::create(
                id: Uuid::v4(),
                name: $command->name()
            );
        } else {
            $task->start();
        }

        $this->taskRepository->save($task);
    }
}