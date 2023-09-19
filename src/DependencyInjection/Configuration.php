<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_temporal_worker');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('worker')
                    ->children()
                        ->scalarNode('dsn')
                        ->defaultValue('localhost:7233')
                        ->end()
                        ->scalarNode('namespace')
                        ->defaultValue('default')
                        ->end()
                    ->end()
                    ->end() // worker
            ->end()
        ;

        return $treeBuilder;
    }
}
