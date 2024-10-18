<?php declare(strict_types=1);

namespace App\Application\Service\Task\EndTask;

use App\Application\Exception\Task\TaskNotExistException;
use App\Domain\Exception\Task\TaskNotInProgressException;
use App\Domain\Exception\Time\TimeEndNotValidException;
use App\Domain\Repository\TaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class EndTask
{
    private TaskRepository $taskRepository;

    public function __construct(
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param EndTaskCommand $command
     * @throws TaskNotExistException
     * @throws TimeEndNotValidException
     * @throws TaskNotInProgressException
     */
    public function __invoke(EndTaskCommand $command): void
    {
        if (!$task = $this->taskRepository->byName(name: $command->name())) {
            throw new TaskNotExistException();
        }

        $task->end();

        $this->taskRepository->save($task);
    }
}