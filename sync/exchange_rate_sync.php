<?php

namespace Finance\sync;

use Finance\core\Currency;

require __DIR__ . '/../autoloader.php';

abstract class ExchangeRateSync extends Sync
{
    protected $currency;

    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    public function setStartDate()
    {
        $query = "SELECT MAX(date) + INTERVAL 1 DAY as date
                  FROM currencies
                  CROSS JOIN currencies as t1 
                  LEFT JOIN currency_rates
                      ON currency_rates.currency_1 = currencies.currency_id
                      AND currency_rates.currency_2 = t1.currency_id
                  WHERE currencies.currency_id <> t1.currency_id
                  AND currencies.currency_id = ?
                  GROUP BY t1.currency_id
                  LIMIT 1";

        $params[] = $this->currency->getId();

        $stmt = $this->conn->runQuery($query, $params);
        $stmt->bind_result($date);
        $stmt->fetch();

        $this->start_date = new \DateTime($date);
    }
}

