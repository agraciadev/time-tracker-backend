<?php

namespace App\Infrastructure\Controller\Api;

use App\Application\Query\Task\GetTasks\GetTasksCommand;
use App\Application\Service\Task\EndTask\EndTaskCommand;
use App\Application\Service\Task\StartTask\StartTaskCommand;
use App\Infrastructure\Attribute\ApiConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/task')]
class TaskController extends ApiController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/all', name: 'api_task_all', methods: ["GET"])]
    #[ApiConfig(secure: false)]
    public function getAll(): Response
    {
        return $this->handleQuery(new GetTasksCommand());
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/start', name: 'api_task_start', methods: ["POST"])]
    #[ApiConfig(secure: false)]
    public function startTask(): Response
    {
        return $this->handleCommand(new StartTaskCommand(
            name: (string)$this->params->get("name")
        ));
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/end', name: 'api_task_end', methods: ["POST"])]
    #[ApiConfig(secure: false)]
    public function endTask(): Response
    {
        return $this->handleCommand(new EndTaskCommand(
            name: (string)$this->params->get("name")
        ));
    }
}
