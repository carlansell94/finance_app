<?php

namespace Finance\sync;

use Finance\core\Market;

require_once __DIR__ . "/../autoloader.php";

$stock_market_list = Market::getFullList();
$stock_market_list->bind_result(
                    $id,
                    $name,
                    $symbol,
                    $exchange_name,
                    $exchange_symbol,
                    $exchange_suffix,
                    $timezone,
                    $open,
                    $close,
                    $price
                );

$stock_market_list->store_result();

while ($stock_market_list->fetch()) {
    $market = new Market($id);
    $sync = MarketPriceSource::fetch();
    $sync->setMarket($market);
    $sync->setStartDate();

    if ($sync->isMarketOpen()) {
        $sync->modifyEndDate('-1 day');
    }

    if ($sync->isRequired()) {
        $sync->run();
    }
}

