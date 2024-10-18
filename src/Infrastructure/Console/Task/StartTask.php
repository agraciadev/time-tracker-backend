<?php

namespace App\Infrastructure\Console\Task;

use App\Application\Service\Task\StartTask\StartTaskCommand;
use App\Infrastructure\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

class StartTask extends AbstractCommand
{
    const ARGUMENT_NAME = "name";

    protected function configure()
    {
        $this
            ->setName('app:task:start')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::REQUIRED, "Name")
            ->setDescription('Start task');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->handleCommand(new StartTaskCommand(
            name: $input->getArgument(self::ARGUMENT_NAME)
        ));

        if ($this->consoleResponseStamp->code() == self::SUCCESS) {
            $this->successMessage("Task started");
        }

        return $this->consoleResponseStamp->code();
    }
}
