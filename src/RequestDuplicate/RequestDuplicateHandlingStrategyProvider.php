<?php

declare(strict_types=1);

namespace Rsumka\RequestLockBundle\RequestDuplicate;

class RequestDuplicateHandlingStrategyProvider
{
    /**
     * @var RequestDuplicateHandlingStrategy[]
     */
    private array $strategies = [];

    private string $activeStrategy;

    public function __construct(string $activeStrategy)
    {
        $this->activeStrategy = $activeStrategy;
    }

    public function addStrategy(RequestDuplicateHandlingStrategy $strategy): void
    {
        $this->strategies[$strategy::getName()] = $strategy;
    }

    public function getStrategyFromConfiguration(): RequestDuplicateHandlingStrategy
    {
        if (!isset($this->strategies[$this->activeStrategy])) {
            throw new \InvalidArgumentException('Invalid request duplicated handling strategy');
        }

        return $this->strategies[$this->activeStrategy];
    }
}
