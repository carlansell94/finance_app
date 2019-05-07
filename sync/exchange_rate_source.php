<?php

namespace Finance\sync;

require_once __DIR__ . '/../autoloader.php';

final class ExchangeRateSource
{
    public static function fetch(): ExchangeRateSync
    {
        return new ExchangeRateSourceXe();
    }
}

