<?php

namespace Finance\sync;

use Finance\core\Stock;

require_once __DIR__ . "/../autoloader.php";

$stock_list = Stock::getAllIds();
$stock_list->bind_result($id);
$stock_list->store_result();

while ($stock_list->fetch()) {
    $stock = new Stock($id);
    $sync = StockPriceSource::fetch();
    $sync->setStock($stock);
    $sync->setStartDate();

    //if ($sync->isMarketOpen()) {
    //    $sync->modifyEndDate('-1 day');
    //}

    if ($sync->isRequired()) {
        $sync->run();
    }
}

