<?php declare(strict_types=1);

namespace App\Domain\Entity;

use Symfony\Component\Uid\Uuid;

abstract class AbstractUuidEntity extends AbstractEntity
{
    /** @var Uuid */
    protected Uuid $id;

    protected function __construct(Uuid $id)
    {
        $this->id = $id;
        parent::__construct();
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}
