<?php

declare(strict_types=1);

namespace Rsumka\RequestLockBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RequestLockExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('rsumka.request_lock.strategy_provider');
        $definition->replaceArgument(0, $config['request_duplicate_handling_strategy']);

        $definition = $container->getDefinition('rsumka.request_lock.request_subscriber');
        $definition->replaceArgument(2, $config['ignored_headers']);
    }
}
