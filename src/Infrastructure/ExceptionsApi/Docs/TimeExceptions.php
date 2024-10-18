<?php declare(strict_types=1);

namespace App\Infrastructure\ExceptionsApi\Docs;

use App\Domain\Exception\Time\TimeEndNotValidException;
use Symfony\Component\HttpFoundation\Response;

class TimeExceptions extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            classname: TimeEndNotValidException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Time end not valid"
        );
    }

    protected function baseError(): string
    {
        return "TIME";
    }
}
