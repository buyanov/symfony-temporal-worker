<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Command;

use Buyanov\SymfonyTemporalWorker\Temporal\Worker\TemporalWorkerInterface;
use Spiral\RoadRunner\Environment\Mode;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'temporal:worker:run')]
final class TemporalWorkerCommand extends Command
{
    public function __construct(private TemporalWorkerInterface $worker)
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addArgument('queue', null, 'The queue of the messages being dispatched', 'default')
            ->setDescription('Run the roadrunner temporal worker')
            ->setHelp(<<<'EOF'
                This command should not be run manually but specified in a <info>.rr.yaml</info>
                configuration file.
                EOF);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (getenv('RR_MODE') !== Mode::MODE_TEMPORAL) {
            $io = new SymfonyStyle($input, $output);

            $io->title('Temporal Messenger Extension');
            $io->error('Command messenger:work should not be run manually');
            $io->writeln('You should reference this command in a <info>.rr.yaml</> configuration file, then run <info>bin/rr serve</>. Example:');
            $io->writeln(<<<'YAML'
                <comment>
                version: "3"
                rpc:
                    listen: tcp://127.0.0.1:6001

                server:
                    command: "php bin/console temporal:worker:run"

                temporal:
                    address: temporal:7233
                    namespace: default
                    activities:
                        num_workers: 5
                    codec: json
                </comment>
                YAML);

            return 1;
        }

        $this->worker->start($input->getArgument('queue'));

        return 0;
    }
}
