<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DuplicateRequestException extends HttpException
{
    public function __construct()
    {
        parent::__construct(500);
    }
}
