<?php

namespace App\Infrastructure\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

final class ConsoleResponseStamp implements StampInterface
{

    const SUCCESS_CODE = 0;

    public function __construct(
        private int $code,
        private array $errorMessages = [],
    )
    {
    }

    public function code(): int
    {
        return $this->code;
    }

    public function errorMessages(): array
    {
        return $this->errorMessages;
    }

    public function addErrorMessage(string $message): void
    {
        $this->errorMessages[] = $message;
    }

}
