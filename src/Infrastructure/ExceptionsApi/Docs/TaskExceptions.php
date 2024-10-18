<?php declare(strict_types=1);

namespace App\Infrastructure\ExceptionsApi\Docs;

use App\Application\Exception\Task\TaskNotExistException;
use App\Domain\Exception\Task\TaskInProgressException;
use App\Domain\Exception\Task\TaskNotInProgressException;
use Symfony\Component\HttpFoundation\Response;

class TaskExceptions extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            classname: TaskNotExistException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Task not exists"
        );

        $this->addError(
            classname: TaskInProgressException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Task in progress"
        );

        $this->addError(
            classname: TaskNotInProgressException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Task not in progress"
        );
    }

    protected function baseError(): string
    {
        return "TASK";
    }
}
