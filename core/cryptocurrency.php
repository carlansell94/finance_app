<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

final class Cryptocurrency
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function getIcon()
    {
        return base64_encode($this->icon);
    }
}

