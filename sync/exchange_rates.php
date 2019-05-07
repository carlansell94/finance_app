<?php

namespace Finance\sync;

use Finance\core\Currency;

require_once __DIR__ . "/../autoloader.php";

$currency_list = Currency::getFullList();
$currency_list->bind_result(
                    $iso_code,
                    $name,
                    $ranking_id,
                    $country_flag,
                    $symbol,
                    $symbol_minor
                );

$currency_list->store_result();

while ($currency_list->fetch()) {
    $currency = new Currency($iso_code);

    $sync = ExchangeRateSource::fetch();
    $sync->setCurrency($currency);
    $start_date = $sync->setStartDate();

    if ($sync->isRequired()) {
        $sync->run();
    }
}

