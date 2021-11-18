<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle;

use Rsumka\RequestLockBundle\DependencyInjection\RequestDuplicateHandlingStrategyCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RequestLockBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RequestDuplicateHandlingStrategyCompilerPass());
    }
}
