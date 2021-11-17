<?php

declare(strict_types=1);

namespace Rsumka\RequestLockBundle\RequestDuplicate;

use Rsumka\RequestLockBundle\Exception\DuplicateRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Lock\LockInterface;

class ThrowExceptionStrategy implements RequestDuplicateHandlingStrategy
{
    public function handle(Request $request, LockInterface $lock): void
    {
        throw new DuplicateRequestException();
    }

    public static function getName(): string
    {
        return 'throw_exception_strategy';
    }
}
