<?php declare(strict_types=1);

use App\Application\Service\Task\StartTask\StartTask;
use App\Application\Service\Task\StartTask\StartTaskCommand;
use App\Domain\Exception\Task\TaskInProgressException;
use App\Tests\Infrastructure\Repository\InMemoryTaskRepository;
use PHPUnit\Framework\TestCase;

final class StartTaskTest extends TestCase
{
    /**
     * @throws TaskInProgressException
     */
    public function testStartCorrectTask(): void
    {
        $taskRepository = new InMemoryTaskRepository();

        $name = 'task 1';

        $service = new StartTask($taskRepository);

        $service(new StartTaskCommand(
            name: $name
        ));

        $this->assertCount(1, $taskRepository->all());
        $this->assertNotNull($taskRepository->inProgress());
        $this->assertEquals($name, $taskRepository->inProgress()->name());
    }


    /**
     * @throws TaskInProgressException
     */
    public function testStartTaskAlreadyInProgress(): void
    {
        $this->expectException(TaskInProgressException::class);

        $taskRepository = new InMemoryTaskRepository();

        $name = 'task 1';

        $service = new StartTask($taskRepository);

        $service(new StartTaskCommand(
            name: $name
        ));

        $service(new StartTaskCommand(
            name: $name
        ));
    }
}
