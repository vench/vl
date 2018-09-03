<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 03.09.18
 * Time: 16:36
 */

namespace src\Decorator;



use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

// Исходя из названия патерна, данный класс  не полжен расширять DataProvider
// А только принимать общий интерфейс с ним и дополнять его поля своими свойствами
//
class DecoratorManager extends DataProvider
{
    //Хорошим тоном считается скрывать все переменные класса
    //доступ довать только через методы
    public $cache;
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Повсей вероятности данный метод является "оберткой" get
     * Тогда его и нужно было переопределить
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            // нет смысла вызывать через parent, тк он не изменен в данном классе
            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt( //Наверное время кэширования можно положить в настройки класса
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            //Логер может быть не передан по умолчанию, поэтому нужно либо проверять
            //либо сделать его обязательным для реализации
            $this->logger->critical('Error');
        }

        return [];
    }

    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}