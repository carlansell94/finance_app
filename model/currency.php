<?php

namespace Finance\model;

use Finance\core\Connection;

require_once __DIR__ . '/../autoloader.php';

final class Currency
{
    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function get(): object
    {
        $query = "SELECT
                      currency_id,
                      iso_code,
                      name,
                      ranking_id,
                      country_flag,
                      symbol,
                      symbol_minor
                  FROM currencies
                  JOIN countries USING (country_id)
                  JOIN ranking USING (ranking_id)";

        if (isset($this->iso_code)) {
            $query .= " WHERE iso_code = '{$this->iso_code}'";
        }

        $query .= " ORDER BY ranking_id ASC";

        $output = $this->conn->runQuery($query);

        return $output->get_result();
    }

    public function setIsoCode(string $iso_code): object
    {
        $this->iso_code = $iso_code;

        return $this;
    }

    public static function getByIsoCode(string $iso_code): object
    {
        $currency = new self();

        $currency->iso_code = $iso_code;

        return $currency->get();
    }

    public static function getSyncList(): array
    {
        $conn = Connection::instance();

        $query = "SELECT
                      currencies.currency_id,
                      iso_code,
                      MAX(coalesce(date, '0000-00-00')) AS date
                  FROM currencies
                  LEFT JOIN currency_rates
                      ON currency_rates.currency_2 = currencies.currency_id
                      AND currency_rates.currency_1 = 3
                  JOIN countries USING (country_id)
                  JOIN ranking USING (ranking_id)
                  WHERE currency_id <> 3
                  GROUP BY currencies.currency_id";

        $output = $conn->runQuery($query);
        $list = $output->get_result();
        $result = array();

        while ($currency = $list->fetch_object("\Finance\core\Currency")) {
            $result[$currency->iso_code] = $currency;
        }

        return $result;
    }

    public static function getCurrencyPairSyncDate(\Finance\core\Currency $currency_1, \Finance\core\Currency $currency_2)
    {
        $conn = Connection::instance();

        $query = "SELECT MAX(date) as date
                  FROM currency_rates
                  WHERE currency_1 = {$currency_1->currency_id}
                  AND currency_2 = {$currency_2->currency_id}";

        $stmt = $conn->runQuery($query);
        $stmt->bind_result($date);
        $stmt->fetch();

        return new \DateTime($date);
    }
}

