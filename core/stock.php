<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

final class Stock
{
    private $conn;
    private $stock_id;
    private $symbol;
    private $name;
    private $sector;
    private $description;
    private $currency;
    
    public function __construct($stock_id = null)
    {
        $this->conn = Connection::instance();
        $this->stock_id = $stock_id;
    }

    public function getId() : int
    {
        return $this->stock_id;
    }

    public function getSymbol() : string
    {
        $query = 'SELECT stock_symbol
                  FROM stock_symbol_list
                  WHERE stock_id = ?';

        $params = array($this->stock_id);

        $stmt = $this->conn->runQuery($query, $params);
        $stmt->bind_result($this->symbol);
        $stmt->fetch();

        return $this->symbol;
    }

    public function getSuffix() : string
    {
        return $this->suffix;
    }

    public function getMarket() : string
    {
        return $this->market;
    }

    public function getLastPriceDate()
    {
        $query = 'SELECT MAX(date)
                  FROM stock_prices
                  WHERE stock_id = ?';

        $params = array($this->stock_id);

        $stmt = $this->conn->runQuery($query, $params);
        $stmt->bind_result($last_price_date);
        $stmt->fetch();

        return $last_price_date;
    }

    public static function getAllIds(): object
    {
        $conn = Connection::instance();

        $query = "SELECT stock_id
                  FROM stock_symbol_list";

        $stmt = $conn->runQuery($query);

        return $stmt;
    }
}

