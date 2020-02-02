<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

final class Currency
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function getCountryFlag()
    {
        return base64_encode($this->country_flag);
    }

    public static function getLastSyncDates(Currency $currency_1, Currency $currency_2 = null): object
    {
        $conn = Connection::instance();

        $query = "SELECT
                      t1.iso_code,
                      MAX(date) AS date
                  FROM currencies
                  CROSS JOIN currencies AS t1
                  LEFT JOIN currency_rates
                      ON currency_rates.currency_1 = currencies.currency_id
                      AND currency_rates.currency_2 = t1.currency_id
                  WHERE currencies.currency_id <> t1.currency_id
                  AND currencies.currency_id = ?";

        $params[] = $currency_1->currency_id;

        if (isset($currency_2)) {
            $query .= " AND t1.currency_id = ?";
            $params[] = $currency_2->currency_id;
        }

        $query .= " GROUP BY t1.iso_code";

        $stmt = $conn->runQuery($query, $params);
        
        return $stmt;
    }
}

