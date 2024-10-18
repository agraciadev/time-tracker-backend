<?php

namespace App\Domain\Entity;

use App\Domain\Exception\Time\TimeEndNotValidException;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class Time extends AbstractUuidEntity
{
    protected Task $task;
    protected DateTimeImmutable $startTime;
    protected ?DateTimeImmutable $endTime = null;

    public static function create(
        Uuid $id,
        Task $task,
        DateTimeImmutable $startTime
    ): self
    {
        $e = new static(
            $id
        );
        $e->task = $task;
        $e->startTime = $startTime;
        return $e;
    }

    /**
     * @return Task
     */
    public function task(): Task
    {
        return $this->task;
    }

    /**
     * @return DateTimeImmutable
     */
    public function startTime(): DateTimeImmutable
    {
        return $this->startTime;
    }


    /**
     * @return DateTimeImmutable|null
     */
    public function endTime(): ?DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * @param DateTimeImmutable $endTime
     * @throws TimeEndNotValidException
     */
    public function setEndTime(DateTimeImmutable $endTime): void
    {
        // Checking the endTime date is correct
        if ($endTime < $this->startTime) {
            throw new TimeEndNotValidException();
        }

        $this->endTime = $endTime;
    }

}
