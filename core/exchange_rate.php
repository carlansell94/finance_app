<?php

namespace Finance\core;

require __DIR__ . '/../autoloader.php';

final class ExchangeRate
{
    private $conn;
    private $id;
    private $date;
    private $currency_1;
    private $currency_2;
    private $rate;
    
    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function setId(int $id): ExchangeRate
    {
        $this->id = $id;

        return $this;
    }

    public function setDate($date): ExchangeRate
    {
        if (is_string($date)) {
            $this->date = $date;
        } else {
            $this->date = $date->format('Y-m-d');
        }

        return $this;
    }

    public function setCurrency1(Currency $currency_1): ExchangeRate
    {
        $this->currency_1 = $currency_1;

        return $this;
    }

    public function setCurrency2(Currency $currency_2): ExchangeRate
    {
        $this->currency_2 = $currency_2;

        return $this;
    }

    public function setRate($rate): ExchangeRate
    {
        $this->rate = $rate;

        return $this;
    }

    public static function getRates($params = null, $values = null)
    {
        $conn = Connection::instance();

        $query = "SELECT
                      date,
                      cur1.iso_code,
                      cur2.iso_code,
                      rate
                  FROM currency_rates
                  JOIN currencies AS cur1 ON currency_rates.currency_1 = cur1.currency_id
                  JOIN currencies AS cur2 ON currency_rates.currency_2 = cur2.currency_id";

        if ($params) {
            $query .= " WHERE " . implode(" AND ", $params);
        }

        $query .= " ORDER BY cur1.iso_code, cur2.iso_code, date DESC";

        $stmt = $conn->runQuery($query, $values);
        
        return $stmt;
    }

    public static function getCurrencyRates($currency, $params = null, $values = null)
    {
        $conn = Connection::instance();

        $query = 'SELECT
                      t1.date,
                      iso_code,
                      t1.rate AS "from",
                      t2.rate AS "to",
                      country_flag
                  FROM (
                      SELECT
                          date,
                          currency_2,
                          rate
                      FROM currency_rates
                      WHERE currency_1 = ?
                  ) t1
                  JOIN (
                      SELECT
                          date,
                          currency_1,
                          rate
                      FROM currency_rates
                      JOIN currencies ON currency_2 = currencies.currency_id
                      WHERE currency_2 = ?
                  ) t2 ON t1.date = t2.date
                      AND t1.currency_2 = t2.currency_1
                  JOIN currencies ON t1.currency_2 = currencies.currency_id
                  JOIN countries USING (country_id)';

        if ($params) {
            $query .= " WHERE " . implode(" AND ", $params) . " ORDER BY t1.date DESC, iso_code";
        } else {
            $query .= " JOIN (
                            SELECT MAX(date) AS md
                            FROM currency_rates
                        ) t3 ON t1.date = t3.md";
        }

        if (isset($values)) {
            array_unshift($values, $currency->getId());
            array_unshift($values, $currency->getId());
        } else {
            $values = array(
                          $currency->getId(),
                          $currency->getId()
                      );
        }

        $stmt = $conn->runQuery($query, $values);

        return $stmt;
    }

    public static function getChange($params = null)
    {
        $conn = Connection::instance();

        $query = 'SELECT
                      iso_code,
                      iso_code_2,
                      date,
                      rate,
                      date_2,
                      rate_2
                  FROM (
                      SELECT
                          c1.iso_code,
                          c2.iso_code AS iso_code_2,
                          date,
                          rate,
                          MAX(date_2) AS date_2,
                          MAX(rate_2) as rate_2,
                          c1.ranking_id,
                          c2.ranking_id AS ranking_id_2
                      FROM (
                          SELECT
                              currency_1,
                              currency_2,
                              date,
                              rate,
                              "" as date_2,
                              "" as rate_2
                          FROM currency_rates
                          WHERE date = (
                              SELECT
                                  date
                              FROM currency_rates AS t2
                              WHERE t2.currency_1 = currency_rates.currency_1
                                  AND t2.currency_2 = currency_rates.currency_2
                              ORDER BY date DESC
                              LIMIT 1 OFFSET 1
                          )
                          UNION
                          SELECT
                              currency_1,
                              currency_2,
                              "",
                              "",
                              date,
                              rate
                          FROM currency_rates
                          WHERE date = IFNULL (
                              (
                                  SELECT
                                      date
                                  FROM currency_rates AS t2
                                  WHERE t2.currency_1 = currency_rates.currency_1
                                      AND t2.currency_2 = currency_rates.currency_2
                                  ORDER BY date DESC
                                  LIMIT 1
                              ),
                          0)
                      ) t5
                      LEFT JOIN currencies c1 ON t5.currency_1 = c1.currency_id
                      LEFT JOIN currencies c2 ON t5.currency_2 = c2.currency_id
                      GROUP BY currency_1, currency_2
                  ) t6';

        if ($params) {
            $query .= " WHERE " . implode(" AND ", $params);
        }

        $stmt = $conn->runQuery($query);

        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO currency_rates (
                      date,
                      currency_1,
                      currency_2,
                      rate
                  )
                  VALUES (?, ?, ?, ?)";

        $values = array(
                      $this->date,
                      $this->currency_1->getId(),
                      $this->currency_2->getId(),
                      $this->rate
                  );

        $result = $this->conn->runQuery($query, $values);

        return $result;
    }

    public function update()
    {
        $query = "UPDATE currency_rates
                  SET
                      rate = ?
                  WHERE date = ?
                  AND currency_1 = ?
                  AND currency_2 = ?";

        $values = array(
                      $this->rate,
                      $this->date,
                      $this->currency_1->getId(),
                      $this->currency_2->getId(),
                  );

        $result = $this->conn->runQuery($query, $values);

        return $result;
    }
}

