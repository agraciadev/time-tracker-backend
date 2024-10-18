<?php declare(strict_types=1);

namespace App\Application\Service\Task\EndTask;

use Symfony\Component\Validator\Constraints as Assert;

final class EndTaskCommand
{
    #[Assert\NotBlank]
    private string $name;

    public function __construct(
        string $name
    )
    {
        $this->name = $name;
    }
    public function name(): string
    {
        return $this->name;
    }
}