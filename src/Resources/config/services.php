<?php

declare(strict_types=1);

use Buyanov\SymfonyTemporalWorker\Command\TemporalWorkerCommand;
use Buyanov\SymfonyTemporalWorker\Temporal\Worker\TemporalWorker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Temporal\Client\WorkflowClientInterface;
use Temporal\WorkerFactory;
use Temporal\Testing\WorkerFactory as TestingWorkerFactory;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $env = $containerConfigurator->env();

    $workerFactoryClass = ($env === 'test')
        ? TestingWorkerFactory::class
        : WorkerFactory::class;

    $services
        ->set('temporal.worker_factory', $workerFactoryClass)
        ->factory([$workerFactoryClass, 'create']);

    $services
        ->set('temporal.worker', TemporalWorker::class)
        ->args([
            service('service_container'),
            service('temporal.worker_factory'),
        ]);

    $services
        ->set('temporal.client.factory', WorkflowClientInterface::class)
        ->args([
            '', // will be filled in with dsn dynamically
            '', // will be filled in with namespace dynamically
        ]);

    $services
        ->set('temporal.worker.command', TemporalWorkerCommand::class)
        ->tag('console.command')
        ->args([
            service('temporal.worker'),
        ]);
};
