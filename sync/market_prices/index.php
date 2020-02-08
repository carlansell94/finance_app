<?php

namespace Finance\sync\MarketPrices;

use Finance\model\Market;

require_once __DIR__ . "/../../autoloader.php";

$markets = Market::getSyncList();

$market_price_sync = MarketPriceSource::fetch();
$market_price_sync->setMarkets($markets);
$market_price_sync->setStartDate();

$diff = $market_price_sync->getEndDate()->format('w') - 5;

if ($market_price_sync->isRequired()) {
    $market_price_sync->run();
}

