<?php declare(strict_types=1);

use App\Application\Exception\Task\TaskNotExistException;
use App\Application\Service\Task\EndTask\EndTask;
use App\Application\Service\Task\EndTask\EndTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Exception\Task\TaskNotInProgressException;
use App\Domain\Exception\Time\TimeEndNotValidException;
use App\Tests\Infrastructure\Repository\InMemoryTaskRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class EndTaskTest extends TestCase
{

    /**
     * @throws TaskNotExistException
     * @throws TaskNotInProgressException
     * @throws TimeEndNotValidException
     */
    public function testEndCorrectTask(): void
    {
        $taskRepository = new InMemoryTaskRepository();

        $name = 'task 1';

        $task = Task::create(
            id: Uuid::v4(),
            name: $name
        );

        $taskRepository->save($task);

        $service = new EndTask($taskRepository);

        $service(new EndTaskCommand(
            name: $name
        ));

        $task = $taskRepository->byName($name);

        $this->assertFalse($task->inProgress());
    }

    /**
     * @throws TaskNotExistException
     * @throws TaskNotInProgressException
     * @throws TimeEndNotValidException
     */
    public function testEndTaskNotExists(): void
    {
        $this->expectException(TaskNotExistException::class);

        $taskRepository = new InMemoryTaskRepository();

        $name = 'task 1';

        $service = new EndTask($taskRepository);

        $service(new EndTaskCommand(
            name: $name
        ));
    }

    /**
     * @throws TaskNotExistException
     * @throws TaskNotInProgressException
     * @throws TimeEndNotValidException
     */
    public function testEndTaskNotInProgress(): void
    {
        $this->expectException(TaskNotInProgressException::class);

        $this->expectException(TaskNotExistException::class);

        $taskRepository = new InMemoryTaskRepository();

        $name = 'task 1';

        $service = new EndTask($taskRepository);

        $service(new EndTaskCommand(
            name: $name
        ));

        $service(new EndTaskCommand(
            name: $name
        ));
    }
}
