<?php

namespace Finance\sync;

use Finance\core\Stock;

require_once __DIR__ . "/../autoloader.php";

$end_date = date('w');

$stock_list = Stock::getAllIds();
$stock_list->bind_result($id);
$stock_list->store_result();

while ($stock_list->fetch()) {
    $stock = new Stock($id);
    
    $sync = StockPriceSource::fetch();
    $sync->setStock($stock);
    $sync->setStartDate();
    
    if ($sync->isMarketOpen() || $end_date == 5) {
        $sync->modifyEndDate('-1 day');
    }
    
    if ($end_date == 6) {
        $sync->modifyEndDate('-2 days');
    }
    
    if ($sync->isRequired()) {
        $sync->run();
    }
}
