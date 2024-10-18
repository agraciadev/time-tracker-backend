<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\ExceptionsApi\HandlerApiExceptions;
use App\Infrastructure\Stamp\ConsoleResponseStamp;
use App\Infrastructure\Stamp\ResponseStamp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ConsoleMiddleware implements MiddlewareInterface
{

    const STATUS_CODE_VALIDATION_ERROR = 1;
    const STATUS_CODE_HANDLER_FAILED = 2;
    const STATUS_CODE_UNEXPECTED_ERROR = -1;

    public function __construct(
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

        $statusCode = ConsoleResponseStamp::SUCCESS_CODE;
        $errorMessages = [];

        try {
            // Execute the handler
            $envelope = $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $e) {
            $statusCode = self::STATUS_CODE_HANDLER_FAILED;
            $data = $this->handlerApiExceptions->handle($e->getPrevious() ?? $e);
            $errorMessages[] = "Error: " . $data["description"];
        } catch (\Exception $e) {
            $statusCode = self::STATUS_CODE_UNEXPECTED_ERROR;
            $data = $this->handlerApiExceptions->handle($e);
            $errorMessages[] = "Error: " . $data["description"];
        }

        return $envelope->with(new ConsoleResponseStamp(code: $statusCode, errorMessages: $errorMessages));
    }
}
