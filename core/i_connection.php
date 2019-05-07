<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

interface IConnection
{
    // Fetch the static connection instance
    public static function instance();

    // Run the provided query, using the given array of parameters
    public function runQuery(string $query, array $param);
}

