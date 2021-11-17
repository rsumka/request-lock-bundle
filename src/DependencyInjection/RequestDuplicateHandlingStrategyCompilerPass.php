<?php

declare(strict_types=1);

namespace Rsumka\RequestLockBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RequestDuplicateHandlingStrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $providerDefinition = $container->findDefinition('rsumka.request_lock.strategy_provider');

        $strategiesIds = $container->findTaggedServiceIds('rsumka.request_duplicate.strategy');

        foreach ($strategiesIds as $id => $tags) {
            $providerDefinition->addMethodCall('addStrategy', [new Reference($id)]);
        }
    }
}
