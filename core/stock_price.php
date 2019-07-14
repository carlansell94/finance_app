<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

class StockPrice
{
    private $conn;
    private $stock;
    private $date;
    private $high;
    private $low;
    private $close;

    public function __construct(Stock $stock)
    {
        $this->conn = Connection::instance();
        $this->stock = $stock;
    }

    public function setPrice($date, $high, $low, $close): StockPrice
    {
        $this->date = $date;
        $this->high = $high;
        $this->low = $low;
        $this->close = $close;

        return $this;
    }

    public function create()
    {
        $query = "INSERT INTO stock_prices (
                      stock_id,
                      date,
                      high,
                      low,
                      close
                  )
                  VALUES (?, ?, ?, ?, ?)";

        $values = array(
                      $this->stock->getId(),
                      $this->date,
                      $this->high,
                      $this->low,
                      $this->close
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

