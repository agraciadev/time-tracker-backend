<?php

namespace App\Infrastructure\Console;

use App\Infrastructure\Stamp\ConsoleResponseStamp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractCommand extends Command
{

    protected OutputInterface $output;
    protected InputInterface $input;
    protected ?Envelope $envelope;
    protected ?ConsoleResponseStamp $consoleResponseStamp;

    public function __construct(
        protected MessageBusInterface $commandBus,
        protected MessageBusInterface $queryBus
    )
    {
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;
        $this->input = $input;
    }

    /**
     * @param object $command
     * @throws ExceptionInterface
     */
    protected function handleCommand(object $command): void
    {
        $this->handle($this->commandBus->dispatch($command));
    }

    /**
     * @param object $command
     * @return Envelope
     * @throws ExceptionInterface
     */
    protected function handleQuery(object $command): void
    {
        $this->handle($this->queryBus->dispatch($command));
    }


    /**
     * @param Envelope $envelope
     */
    private function handle(Envelope $envelope): void
    {
        /** @var ConsoleResponseStamp $stamp */
        $stamp = $envelope->last(ConsoleResponseStamp::class);

        if (!$stamp instanceof ConsoleResponseStamp) {
            throw new \RuntimeException(sprintf('This controller only works after "%s" stamp. Did you forget to add a middleware?', ConsoleResponseStamp::class));
        }

        if ($stamp->errorMessages()) {
            $this->output->writeln(
                messages: array_map(function ($m) {
                    return "<error>" . $m . "</error>";
                }, $stamp->errorMessages())
            );
        }

        $this->envelope = $envelope;
        $this->consoleResponseStamp = $stamp;
    }

    protected function successMessage(string $message)
    {
        $this->output->writeln(
            messages: "<fg=green>" . $message . "</>"
        );
    }
}
