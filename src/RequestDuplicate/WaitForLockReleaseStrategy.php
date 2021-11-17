<?php

declare(strict_types=1);

namespace Rsumka\RequestLockBundle\RequestDuplicate;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Lock\LockInterface;

class WaitForLockReleaseStrategy implements RequestDuplicateHandlingStrategy
{
    public function handle(Request $request, LockInterface $lock): void
    {
        $acquired = $lock->acquire();

        while (!$acquired) {
            usleep(100000);
            $acquired = $lock->acquire();
        }
    }

    public static function getName(): string
    {
        return 'wait_for_lock_release_strategy';
    }
}
