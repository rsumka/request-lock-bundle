<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle\RequestDuplicate;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Lock\LockInterface;

interface RequestDuplicateHandlingStrategy
{
    public function handle(Request $request, LockInterface $lock): void;

    public static function getName(): string;
}
