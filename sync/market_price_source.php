<?php

namespace Finance\sync;

require_once __DIR__ . '/../autoloader.php';

final class MarketPriceSource
{
    public static function fetch(): MarketPriceSync
    {
        return new MarketPriceSourceYahoo();
    }
}

