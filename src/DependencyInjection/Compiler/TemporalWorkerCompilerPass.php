<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\DependencyInjection\Compiler;

use Buyanov\SymfonyTemporalWorker\Temporal\Worker\TemporalWorker;
use Buyanov\SymfonyTemporalWorker\Temporal\Worker\TemporalWorkerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemporalWorkerCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container): void
    {
        $definition = $container
            ->register(TemporalWorkerInterface::class, TemporalWorker::class)
            ->addArgument(new Reference('service_container'))
            ->setPublic(false);

        $taggedActivities = $container->findTaggedServiceIds('temporal.activity');

        foreach ($taggedActivities as $id => $tags) {
            $container->findDefinition($id)->setPublic(true);
            $definition->addMethodCall('addActivity', [$id]);
        }

        $taggedWorkflows = $container->findTaggedServiceIds('temporal.workflow');

        foreach ($taggedWorkflows as $id => $tags) {
            $definition->addMethodCall('addWorkflow', [$id]);
        }
    }
}