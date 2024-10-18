<?php

namespace App\Domain\Entity;

use App\Domain\Exception\Task\TaskInProgressException;
use App\Domain\Exception\Task\TaskNotInProgressException;
use App\Domain\Exception\Time\TimeEndNotValidException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Uid\Uuid;

class Task extends AbstractUuidEntity
{
    protected string $name;
    protected Collection $times;
    protected bool $inProgress;

    public static function create(
        Uuid $id,
        string $name
    ): self
    {
        $e = new static(
            $id
        );
        $e->name = $name;
        $e->inProgress = false;
        $e->times = new ArrayCollection();
        $e->start();
        return $e;
    }

    /**
     * @return Collection
     */
    public function times(): Collection
    {
        return $this->times;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    public function inProgress(): bool
    {
        return $this->inProgress;
    }

    /**
     * @throws TaskInProgressException
     */
    public function start(): void
    {
        // Check if the task is already in progress
        if ($this->inProgress()) {
            throw new TaskInProgressException();
        }

        // Create start time
        $this->times->add(Time::create(
            id: Uuid::v4(),
            task: $this,
            startTime: new DateTimeImmutable()
        ));

        // Set task in progress
        $this->inProgress = true;
    }

    /**
     * @throws TaskNotInProgressException
     * @throws TimeEndNotValidException
     */
    public function end(): void
    {
        // Check if the task is in progress
        if (!$this->inProgress()) {
            throw new TaskNotInProgressException();
        }

        // Get the last not ended time
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('endTime'))
            ->setMaxResults(1);

        /** @var Time $time */
        $time = $this->times->matching($criteria)->first();

        // Set endTime
        $time->setEndTime(new DateTimeImmutable());

        // Set task not in progress
        $this->inProgress = false;
    }

}
