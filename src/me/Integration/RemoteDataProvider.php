<?php

namespace src\me\Integration;

/**
 * Class RemoteDataProvider
 * @package src\me\Integration
 */
class RemoteDataProvider implements DataProvider
{

    private $host;
    private $user;
    private $password;

    /**
     * RemoteDataProvider constructor.
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
     * @param array $request
     * @return array
     */
    public function get(array $request)
    {
        //TODO returns a response from external service
        return [];
    }
}