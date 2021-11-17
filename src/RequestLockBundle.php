<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle;

use Rsumka\RequestLockBundle\DependencyInjection\RequestDuplicateHandlingStrategyCompilerPass;
use Rsumka\RequestLockBundle\RequestDuplicate\RequestDuplicateHandlingStrategy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RequestLockBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerForAutoconfiguration(RequestDuplicateHandlingStrategy::class)
            ->addTag('rsumka.request_duplicate.strategy');

        $container->addCompilerPass(new RequestDuplicateHandlingStrategyCompilerPass());
    }
}
