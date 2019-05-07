<?php

namespace Finance\sync;

require_once __DIR__ . '/../autoloader.php';

abstract class Sync
{
    protected $conn;
    protected $start_date;
    protected $end_date;

    public function __construct()
    {
        $this->conn = \Finance\core\Connection::instance();
        $this->end_date = new \DateTime("midnight");
    }

    final function isRequired(): bool
    {
        if ($this->start_date > $this->end_date) {
            return false;
        }

        return true;
    }

    abstract public function setStartDate();
}

