<?php declare(strict_types=1);

use App\Domain\Entity\Task;
use App\Domain\Exception\Task\TaskInProgressException;
use App\Domain\Exception\Task\TaskNotInProgressException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class TaskTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $name = 'task 1';

        $task = Task::create(
            id: Uuid::v4(),
            name: $name
        );

        $this->assertSame($name, $task->name());
        $this->assertTrue($task->inProgress());
        $this->assertTrue($task->times()->count() == 1);
    }

    public function testCanBeEnded(): void
    {
        $name = 'task 1';

        $task = Task::create(
            id: Uuid::v4(),
            name: $name
        );

        $this->assertTrue($task->inProgress());

        $task->end();

        $this->assertFalse($task->inProgress());
    }

    public function testTaskAlreadyEnded(): void
    {
        $this->expectException(TaskNotInProgressException::class);

        $name = 'task 1';

        $task = Task::create(
            id: Uuid::v4(),
            name: $name
        );

        $task->end();

        $task->end();
    }

    public function testTaskAlreadyStarted(): void
    {
        $this->expectException(TaskInProgressException::class);

        $name = 'task 1';

        $task = Task::create(
            id: Uuid::v4(),
            name: $name
        );

        $task->start();
    }
}
