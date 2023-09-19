<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker;

use Buyanov\SymfonyTemporalWorker\DependencyInjection\Compiler\TemporalWorkerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class TemporalWorkerBundle extends AbstractBundle
{

    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder
    ): void {
        $container->import(__DIR__ . '/Resources/config/services.xml');
    }


    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TemporalWorkerCompilerPass());
    }
}