<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TemporalWorkerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('temporal.worker');

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