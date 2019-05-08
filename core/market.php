<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

final class Market
{
    private $conn;
    private $id;
    private $name;
    private $symbol;
    private $exchange;
    
    public function __construct($input)
    {
        $this->conn = Connection::instance();

        if (is_numeric($input)) {
            $this->id = $input;
        } else if (preg_match('/^[a-zA-Z0-9]{4}$/', $input)) {
            $this->symbol = $input;
        }
    }

    public function getId(): int
    {
        if (isset($this->id)) {
            return $this->id;
        }

        if (!isset($this->symbol)) {
            return false;
        }

        $query = "SELECT
                     market_id
                  FROM stock_markets
                  WHERE market_symbol = ?";

        $values[] = $this->symbol;

        $stmt = $this->conn->runQuery($query, $values);
        $stmt->bind_result($this->id);
        $stmt->fetch();

        if (!$this->id) {
            return 0;
        }

        return $this->id;
    }

    public function getSymbol(): string
    {
        if (isset($this->symbol)) {
            return $this->symbol;
        }

        if (!isset($this->id)) {
            return false;
        }

        $query = "SELECT
                      market_symbol
                  FROM stock_markets
                  WHERE market_id = ?";

        $values[] = $this->id;

        $stmt = $this->conn->runQuery($query, $values);
        $stmt->bind_result($this->symbol);
        $stmt->fetch();

        return $this->symbol;
    }

    public static function getFullList(): object
    {
        $conn = Connection::instance();

        $query = "SELECT
                      market_id,
                      market_name,
                      market_symbol,
                      exchange_name,
                      exchange_symbol,
                      exchange_suffix
                  FROM stock_markets
                  JOIN stock_exchanges USING (exchange_id)";

        $stmt = $conn->runQuery($query);

        return $stmt;
    }
}

