<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\ExceptionsApi\HandlerApiExceptions;
use App\Infrastructure\Listener\ApiListener;
use App\Infrastructure\Stamp\ResponseStamp;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class ApiMiddleware implements MiddlewareInterface
{

    public function __construct(
        private SerializerInterface $serializer,
        private ApiListener $apiListener,
        private HandlerApiExceptions $handlerApiExceptions
    )
    {
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     * @return Envelope
     * @throws ExceptionInterface
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $status = Response::HTTP_OK;
        $data = [];

        try {
            // Execute the handler
            $envelope = $stack->next()->handle($envelope, $stack);

            // Check the handler is executed (we have the HandleStamp in the envelope)
            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            $data = $stamp->getResult();
        } catch (HandlerFailedException $e) {
            $data = $this->handlerApiExceptions->handle($e->getPrevious() ?? $e);
            $status = $data['status'];
        } catch (\Exception $e) {
            $data = $this->handlerApiExceptions->handle($e);
            $status = $data['status'];
        }

        // Prepare data for serializer
        $context = SerializationContext::create()->enableMaxDepthChecks();

        $context->setGroups(array_merge(["default"], ($this->apiListener->getClass())->serializerGroups()));

        return $envelope->with(new ResponseStamp(
                response: new JsonResponse(
                    data: $this->serializer->serialize($data ?? [], 'json', $context),
                    status: $status,
                    json: true
                )
            )
        );
    }
}
