<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle\EventSubscriber;

use Rsumka\RequestLockBundle\RequestDuplicate\RequestDuplicateHandlingStrategyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Lock\LockFactory;

class RequestSubscriber implements EventSubscriberInterface
{
    private LockFactory $lockFactory;
    private RequestDuplicateHandlingStrategyProvider $strategyProvider;
    private ?array $ignoredHeaders;

    public function __construct(
        LockFactory $lockFactory,
        RequestDuplicateHandlingStrategyProvider $strategyProvider,
        ?array $ignoredHeaders = null
    ) {
        $this->lockFactory = $lockFactory;
        $this->strategyProvider = $strategyProvider;
        $this->ignoredHeaders = $ignoredHeaders;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest'
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = clone $event->getRequest();

        if (strtolower($request->getMethod()) === 'get') {
            return;
        }

        if (is_array($this->ignoredHeaders) && count($this->ignoredHeaders) > 0) {
            foreach ($this->ignoredHeaders as $header) {
                $request->headers->remove($header);
            }
        }

        $key = md5($request->__toString());

        $lock = $this->lockFactory->createLock($key);
        if (!$lock->acquire()) {
            $requestDuplicateHandlingStrategy = $this->strategyProvider->getStrategyFromConfiguration();
            $requestDuplicateHandlingStrategy->handle($request, $lock);
        }
    }
}
