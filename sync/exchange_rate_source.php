<?php

namespace Finance\sync;

require __DIR__ . '/../autoloader.php';

final class ExchangeRateSource
{
    public static function fetch(): ExchangeRateSync
    {
        return new ExchangeRateSourceXe();
    }
}

