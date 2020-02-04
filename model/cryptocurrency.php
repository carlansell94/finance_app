<?php

namespace Finance\model;

use Finance\core\Connection;

require_once __DIR__ . '/../autoloader.php';

final class Cryptocurrency
{
    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function get(): object
    {
        $query = "SELECT
                      crypto_id,
                      symbol,
                      name,
                      icon,
                      creation_date
                  FROM cryptocurrencies";

        $output = $this->conn->runQuery($query);

        return $output->get_result();
    }

    public static function getSyncList(): array
    {
        $conn = Connection::instance();

        $query = "SELECT
                      crypto_id,
                      symbol,
                      GREATEST(creation_date, MAX(coalesce(date, '0000-00-00'))) AS date
                  FROM cryptocurrencies
                  LEFT JOIN cryptocurrency_rates USING (crypto_id)
                  GROUP BY crypto_id";

        $output = $conn->runQuery($query);
        $list = $output->get_result();
        $result = array();

        while ($currency = $list->fetch_object("\Finance\core\Cryptocurrency")) {
            $result[$currency->symbol] = $currency;
        }

        return $result;
    }
}

