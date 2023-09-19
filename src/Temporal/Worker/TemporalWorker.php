<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Temporal\Worker;

use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Temporal\Worker\WorkerFactoryInterface;
use Temporal\WorkerFactory;

final class TemporalWorker implements TemporalWorkerInterface
{
    private WorkerFactoryInterface $factory;
    /**
     * @var array<class-string> $workflows
     */
    private array $workflows = [];

    /**
     * @var array<class-string> $activities
     */
    private array $activities = [];
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container,
    ) {
        $this->container = $container;
        $this->factory   = WorkerFactory::create();
    }

    public function start(string $queue = 'default'): void
    {
        $worker = $this->factory->newWorker($queue);
        $worker->registerWorkflowTypes(...$this->workflows);

        foreach ($this->activities as $activity) {
            $worker->registerActivity(
                $activity,
                fn (ReflectionClass $class) => $this->container->get($class->getName())
            );
        }

        $this->factory->run();
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
