<?php

namespace App\Infrastructure\Controller\Api;

use App\Infrastructure\Controller\CoreController;
use App\Infrastructure\Attribute\ApiConfig;
use App\Infrastructure\Stamp\ResponseStamp;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[ApiConfig(secure: false)]
abstract class ApiController extends CoreController
{
    public function __construct(
        protected MessageBusInterface $commandBus,
        protected MessageBusInterface $queryBus,
        RequestStack $requestStack
    )
    {
        parent::__construct($requestStack);
    }

    /**
     * @param object $command
     * @return Response
     * @throws ExceptionInterface
     */
    protected function handleCommand(object $command): Response
    {
        return $this->handle($this->commandBus->dispatch($command));
    }

    /**
     * @param object $command
     * @return Response
     * @throws ExceptionInterface
     */
    protected function handleQuery(object $command): Response
    {
        return $this->handle($this->queryBus->dispatch($command));
    }


    /**
     * @param Envelope $envelope
     * @return Response
     */
    private function handle(Envelope $envelope){
        /** @var ResponseStamp $stamp */
        $stamp = $envelope->last(ResponseStamp::class);

        if (!$stamp instanceof ResponseStamp) {
            throw new \RuntimeException(sprintf('This controller only works after "%s" stamp. Did you forget to add a middleware?', ResponseStamp::class));
        }

        return $stamp->response();
    }
}
