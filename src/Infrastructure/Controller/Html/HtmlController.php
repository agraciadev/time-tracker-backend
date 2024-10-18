<?php

namespace App\Infrastructure\Controller\Html;

use App\Infrastructure\Controller\CoreController;
use App\Infrastructure\Attribute\HtmlConfig;
use App\Infrastructure\Stamp\ResponseStamp;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[HtmlConfig(secure: false)]
abstract class HtmlController extends CoreController
{
    protected MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus,
        RequestStack $requestStack
    )
    {
        $this->messageBus = $messageBus;
        parent::__construct($requestStack);
    }

    /**
     * @param object $command
     * @return Response
     * @throws ExceptionInterface
     */
    protected function handle(object $command): Response
    {
        $envelope = $this->messageBus->dispatch($command);

        /** @var ResponseStamp $stamp */
        $stamp = $envelope->last(ResponseStamp::class);

        if (!$stamp instanceof ResponseStamp) {
            throw new \RuntimeException(sprintf('This controller only works after "%s" stamp. Did you forget to add a middleware?', ResponseStamp::class));
        }

        return $stamp->response();
    }
}
