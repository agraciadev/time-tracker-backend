<?php declare(strict_types=1);

use App\Application\Query\Task\GetTasks\GetTasksCommand;
use App\Domain\Entity\Task;
use App\Tests\Infrastructure\Repository\InMemoryTaskRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class GetTasksTest extends TestCase
{
    public function testReturnsCorrectList(): void
    {
        $taskRepository = new InMemoryTaskRepository();

        $nameTask1 = 'task 1';

        $task1 = Task::create(
            id: Uuid::v4(),
            name: $nameTask1
        );

        $task1->end();

        $nameTask2 = 'task 2';

        $task2 = Task::create(
            id: Uuid::v4(),
            name: $nameTask2
        );

        $task2->end();

        $taskRepository->save($task1);
        $taskRepository->save($task2);

        $query = new App\Application\Query\Task\GetTasks\GetTasks($taskRepository);
        $tasks = $query(new GetTasksCommand());

        $this->assertCount(2, $tasks);
    }
}
