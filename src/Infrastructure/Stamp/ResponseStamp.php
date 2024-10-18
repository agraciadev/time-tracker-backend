<?php

namespace App\Infrastructure\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\HttpFoundation\Response;

final class ResponseStamp implements StampInterface
{
    public function __construct(
        private Response $response
    ) {
    }

    public function response(): Response
    {
        return $this->response;
    }

}
