<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker;

use Buyanov\SymfonyTemporalWorker\Attribute\ActivityInboundInterceptor;
use Buyanov\SymfonyTemporalWorker\DependencyInjection\Compiler\TemporalWorkerCompilerPass;
use Buyanov\SymfonyTemporalWorker\DependencyInjection\SymfonyTemporalWorkerExtension;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SymfonyTemporalWorkerBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SymfonyTemporalWorkerExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TemporalWorkerCompilerPass());

        $container->registerAttributeForAutoconfiguration(
            ActivityInboundInterceptor::class,
            static function (
                ChildDefinition $definition,
                ActivityInboundInterceptor $attribute,
                ReflectionClass $reflector
            ): void {
                $definition->addTag(ActivityInboundInterceptor::TAG);
            }
        );
    }
}
