<?php

namespace src\me\Integration;

/**
 * Interface DataProvider
 * @package src\me\Integration
 */
interface DataProvider
{
    /**
     * @param array $request
     * @return array
     */
    public function get(array $request);
}
