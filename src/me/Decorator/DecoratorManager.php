<?php

namespace src\me\Decorator;

use src\me\Integration\DataProvider;
use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

/**
 * Class DecoratorManager
 * @package src\me\Decorator
 */
class DecoratorManager
{

    private $cache;
    private $logger;
    private $provider;
    private $expireTime = '+1 day';

    /**
     * DecoratorManager constructor.
     * @param DataProvider $provider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface|null $logger
     */
    public function __construct(DataProvider $provider, CacheItemPoolInterface $cache, LoggerInterface $logger = null)
    {
        $this->provider = $provider;
        $this->cache = $cache;
        $this->setLogger($logger);
    }


    /**
     * @param string $expireTime
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->provider->get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify($this->expireTime)
                );

            return $result;
        } catch (Exception $e) {
            if (is_null($this->logger)) {
                throw $e;
            }
            $this->logger->critical('Error');
        }

        return [];
    }

    /**
     * @param array $input
     * @return string
     */
    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}