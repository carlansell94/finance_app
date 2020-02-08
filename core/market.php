<?php

namespace Finance\core;

final class Market
{
    private $conn;
    
    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function isOpen(): bool
    {
        if (!isset($this->open) || !isset($this->close) || !isset($this->timezone)) {
            return false;
        }

        $tz_open = new \DateTime($this->open, new \DateTimeZone($this->timezone));
        $tz_close = new \DateTime($this->close, new \DateTimeZone($this->timezone));
        $now = new \DateTime();

        if ($now >= $tz_open && $now <= $tz_close && $now->format('N') < 6) {
            return true;
        }

        return false;
    }
}

