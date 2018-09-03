<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 03.09.18
 * Time: 16:35
 */

namespace src\Integration;

/**
 * Class DataProvider
 * @package src\Integration
 */
class DataProvider
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * По всей вероятности данный метод является абстрактным
     * @param array $request
     *
     * @return array
     */
    public function get(array $request)
    {
// returns a response from external service
    }

}