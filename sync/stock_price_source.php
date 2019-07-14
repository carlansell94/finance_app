<?php

namespace Finance\sync;

require_once __DIR__ . '/../autoloader.php';

final class StockPriceSource
{
    public static function fetch(): StockPriceSync
    {
        return new StockPriceSourceYahoo();
    }
}

