<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class TemporalWorkerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );

        $loader->load('services.php');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('temporal.worker.factory');
        $definition->replaceArgument(0, $config['worker']['dsn']);
        $definition->replaceArgument(1, $config['worker']['namespace']);
    }
}