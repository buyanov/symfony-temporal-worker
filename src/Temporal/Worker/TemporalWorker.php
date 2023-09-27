<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Temporal\Worker;

use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Temporal\Worker\WorkerFactoryInterface;

final class TemporalWorker implements TemporalWorkerInterface
{
    /**
     * @var array<class-string> $workflows
     */
    private array $workflows = [];

    /**
     * @var array<class-string> $activities
     */
    private array $activities = [];

    public function __construct(
        private ContainerInterface $container,
        private WorkerFactoryInterface $workerFactory
    ) {
    }

    public function start(string $queue = 'default'): void
    {
        $worker = $this->workerFactory->newWorker($queue);
        $worker->registerWorkflowTypes(...$this->workflows);

        foreach ($this->activities as $activity) {
            $worker->registerActivity(
                $activity,
                fn (ReflectionClass $class) => $this->container->get($class->getName())
            );
        }

        $this->workerFactory->run();
    }

    public function addActivity(string $className): void
    {
        $this->activities[] = $className;
    }

    public function addWorkflow(string $className): void
    {
        $this->workflows[] = $className;
    }
}
