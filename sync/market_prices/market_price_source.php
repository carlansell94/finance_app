<?php

namespace Finance\sync\MarketPrices;

final class MarketPriceSource
{
    public static function fetch(): MarketPriceSync
    {
        return new MarketPriceSourceYahoo();
    }
}

