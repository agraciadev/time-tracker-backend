<?php

namespace App\Infrastructure\Console\Task;

use App\Application\Query\Task\GetTasks\GetTasksCommand;
use App\Domain\Entity\Task;
use App\Domain\Entity\Time;
use App\Infrastructure\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class LisTasks extends AbstractCommand
{
    const SECONDS_IN_A_DAY = 86400;
    const SECONDS_IN_AN_HOUR = 3600;
    const SECONDS_IN_AN_MINUTE = 60;

    protected function configure()
    {
        $this
            ->setName('app:task:list')
            ->setDescription('List all tasks');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->handleQuery(new GetTasksCommand());

        if ($this->consoleResponseStamp->code() == self::SUCCESS) {
            /** @var HandledStamp $stamp */
            $handleStamp = $this->envelope->last(HandledStamp::class);
            $tasks = $handleStamp->getResult();

            $rows = [];

            /** @var Task $task */
            foreach ($tasks as $task) {
                $totalSeconds = 0;

                /** @var Time $time */
                foreach ($task->times() as $time) {
                    $endTime = $time->endTime() ?: (new \DateTime("now"));
                    $totalSeconds += $endTime->getTimestamp() - $time->startTime()->getTimestamp();
                }

                $days = floor($totalSeconds / self::SECONDS_IN_A_DAY);
                $remainingSeconds = $totalSeconds % self::SECONDS_IN_A_DAY;
                $hours = floor($remainingSeconds / self::SECONDS_IN_AN_HOUR);
                $remainingSeconds = $remainingSeconds % self::SECONDS_IN_AN_HOUR;
                $minutes = floor($remainingSeconds / self::SECONDS_IN_AN_MINUTE);
                $seconds = $remainingSeconds % self::SECONDS_IN_AN_MINUTE;

                $rows[] = [
                    $task->name(),
                    $task->inProgress() ? "In progress" : "Ended",
                    "$days Days $hours Hours $minutes Minutes $seconds Seconds"
                ];
            }


            $io = new SymfonyStyle($input, $output);

            $io->table(
                ['Task', 'Status', "Time"],
                $rows
            );
        }

        return $this->consoleResponseStamp->code();
    }
}
