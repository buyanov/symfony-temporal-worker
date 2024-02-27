<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Temporal\Worker;

use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Temporal\Interceptor\SimplePipelineProvider;
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

    /**
     * @var array<class-string> $interceptors
     */
    private array $interceptors = [];

    public function __construct(
        private ContainerInterface $container,
        private WorkerFactoryInterface $workerFactory
    ) {
    }

    public function start(string $queue = 'default'): void
    {
        $preparedInterceptors = [];
        foreach ($this->interceptors as $interceptor) {
            $preparedInterceptors[] = $this->container->get($interceptor);
        }

        $simplePipelineProvider = new SimplePipelineProvider($preparedInterceptors);

        $worker = $this->workerFactory->newWorker($queue, interceptorProvider: $simplePipelineProvider);

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

    public function addInterceptor(string $className): void
    {
        $this->interceptors[] = $className;
    }
}
