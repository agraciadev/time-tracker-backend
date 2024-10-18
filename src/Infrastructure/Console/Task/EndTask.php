<?php

namespace App\Infrastructure\Console\Task;

use App\Application\Service\Task\EndTask\EndTaskCommand;
use App\Infrastructure\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

class EndTask extends AbstractCommand
{
    const ARGUMENT_NAME = "name";

    protected function configure()
    {
        $this
            ->setName('app:task:end')
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
        $this->handleCommand(new EndTaskCommand(
            name: $input->getArgument(self::ARGUMENT_NAME)
        ));

        if ($this->consoleResponseStamp->code() == self::SUCCESS) {
            $this->successMessage("Task ended");
        }

        return $this->consoleResponseStamp->code();
    }
}
