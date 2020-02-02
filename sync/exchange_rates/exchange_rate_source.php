<?php

namespace Finance\sync\ExchangeRates;

final class ExchangeRateSource
{
    public static function fetch(): ExchangeRateSync
    {
        return new ExchangeRateSourceXe();
    }
}
