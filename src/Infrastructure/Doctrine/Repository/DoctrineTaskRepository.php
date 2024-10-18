<?php declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepository;
use Doctrine\Common\Collections\Collection;

class DoctrineTaskRepository extends AbstractDoctrineRepository implements TaskRepository
{
    /**
     * @param Task $task
     */
    public function save(Task $task): void
    {
        $this->em->persist($task);
    }

    /**
     * @param string $name
     * @return Task|null
     */
    public function byName(string $name): ?Task
    {
        return $this->em
            ->createQuery("SELECT t FROM App\Domain\Entity\Task t WHERE t.name = :name")
            ->setParameters([
                'name' => $name,
            ])
            ->getOneOrNullResult();
    }

    public function all(): array
    {
        return $this->em
            ->createQuery("SELECT t FROM App\Domain\Entity\Task t")
            ->getResult();
    }

    public function inProgress(): ?Task
    {
        return $this->em
            ->createQuery("SELECT t FROM App\Domain\Entity\Task t WHERE t.inProgress = true")
            ->getOneOrNullResult();
    }
}
