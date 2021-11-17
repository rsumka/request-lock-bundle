<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle\DependencyInjection;

use Rsumka\RequestLockBundle\RequestDuplicate\WaitForLockReleaseStrategy;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('rsumka_request_lock');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('request_duplicate_handling_strategy')
                    ->defaultValue(WaitForLockReleaseStrategy::getName())
                    ->end()
                ->arrayNode('ignored_headers')
                    ->defaultNull()
                    ->end()
            ->end()
        ;
    }
}
