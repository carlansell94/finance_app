<?php

namespace Finance\core;

require __DIR__ . '/../autoloader.php';

final class Currency
{
    private $conn;
    private $id;
    private $iso_code;
    private $name;
    private $symbol;
    private $minor_symbol;
    private $rank;
    private $country;

    public function __construct($input)
    {
        $this->conn = Connection::instance();

        if (is_numeric($input)) {
            $this->id = $input;
        } else if (preg_match('/^[a-zA-Z]{3}$/', $input)) {
            $this->iso_code = $input;
        }
    }

    public function getId(): int
    {
        if (isset($this->id)) {
            return $this->id;
        }

        if (!isset($this->iso_code)) {
            return false;
        }

        $query = "SELECT
                      currency_id
                  FROM currencies
                  WHERE iso_code = ?";

        $values[] = $this->iso_code;

        $stmt = $this->conn->runQuery($query, $values);
        $stmt->bind_result($this->id);
        $stmt->fetch();

        if (!$this->id) {
            return 0;
        }

        return $this->id;
    }

    public function getIsoCode()
    {
        if (isset($this->iso_code)) {
            return $this->iso_code;
        }

        if (!isset($this->id)) {
            return false;
        }

        $query = "SELECT
                      iso_code
                  FROM currencies
                  WHERE currency_id = ?";

        $values[] = $this->id;

        $stmt = $this->conn->runQuery($query, $values);
        $stmt->bind_result($this->iso_code);
        $stmt->fetch();

        return $this->iso_code;
    }

    public static function getFullList(): object
    {
        $conn = Connection::instance();

        $query = "SELECT
                      iso_code,
                      name,
                      ranking_id,
                      country_flag,
                      symbol,
                      symbol_minor
                  FROM currencies
                  JOIN countries USING (country_id)
                  JOIN ranking USING (ranking_id)";

        $stmt = $conn->runQuery($query);

        return $stmt;
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

        $params[] = $currency_1->getId();

        if (isset($currency_2)) {
            $query .= " AND t1.currency_id = ?";
            $params[] = $currency_2->getId();
        }

        $query .= " GROUP BY t1.iso_code";

        $stmt = $conn->runQuery($query, $params);
        
        return $stmt;
    }
}

