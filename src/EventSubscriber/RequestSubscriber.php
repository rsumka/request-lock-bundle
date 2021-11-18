<?php
declare(strict_types=1);

namespace Rsumka\RequestLockBundle\EventSubscriber;

use Rsumka\RequestLockBundle\RequestDuplicate\RequestDuplicateHandlingStrategyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Lock\Lock;
use Symfony\Component\Lock\LockFactory;

class RequestSubscriber implements EventSubscriberInterface
{
    private LockFactory $lockFactory;
    private RequestDuplicateHandlingStrategyProvider $strategyProvider;
    private array $ignoredHeaders;
    private ?Lock $lock = null;

    public function __construct(
        LockFactory $lockFactory,
        RequestDuplicateHandlingStrategyProvider $strategyProvider,
        array $ignoredHeaders = []
    ) {
        $this->lockFactory = $lockFactory;
        $this->strategyProvider = $strategyProvider;
        $this->ignoredHeaders = $ignoredHeaders;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
            KernelEvents::TERMINATE => 'onTerminate'
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = clone $event->getRequest();

        if (strtolower($request->getMethod()) === 'get') {
            return;
        }

        if (count($this->ignoredHeaders) > 0) {
            foreach ($this->ignoredHeaders as $header) {
                $request->headers->remove($header);
            }
        }

        $key = md5($request->__toString());

        $this->lock = $this->lockFactory->createLock($key);
        if (!$this->lock->acquire()) {
            $requestDuplicateHandlingStrategy = $this->strategyProvider->getStrategyFromConfiguration();
            $requestDuplicateHandlingStrategy->handle($request, $this->lock);
        }
    }

    public function onTerminate(): void
    {
        if ($this->lock !== null) {
            $this->lock->release();
        }
    }
}
