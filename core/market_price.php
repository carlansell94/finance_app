<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

class MarketPrice
{
    private $conn;
    private $market;
    private $date;
    private $price;

    public function __construct(Market $market) {
        $this->conn = Connection::instance();
        $this->market = $market;
    }

    public function setPrice($date, $price): MarketPrice
    {
        $this->date = $date;
        $this->price = $price;

        return $this;
    }

    public function create()
    {
        $query = "INSERT INTO stock_market_prices (
                      market_id,
                      date,
                      price
                  )
                  VALUES (?, ?, ?)";

        $values = array(
                      $this->market->getId(),
                      $this->date,
                      $this->price
                  );

        $result = $this->conn->runQuery($query, $values);

        return $result;
    }

    public static function getAll()
    {
        $query = "SELECT
                      date,
                      market_name,
                      price
                  FROM stock_market_prices
                  JOIN stock_markets USING (market_id)";

        $stmt = $this->conn->runQuery($query);
        
        return $stmt;
    }
}

