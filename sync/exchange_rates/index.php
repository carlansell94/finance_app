<?php

namespace Finance\sync\ExchangeRates;

use Finance\model\Currency;

require_once __DIR__ . "/../../autoloader.php";

$base_currency = Currency::getByIsoCode('USD')->fetch_object("\Finance\core\Currency");
$currencies = Currency::getSyncList();

$exchange_rate_sync = ExchangeRateSource::fetch();
$exchange_rate_sync->setCurrencyList($currencies);
$exchange_rate_sync->setStartDate();

if ($exchange_rate_sync->isRequired()) {
    $exchange_rate_sync->setBaseCurrency($base_currency);
    $exchange_rate_sync->run();
}

