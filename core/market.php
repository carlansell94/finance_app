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
        } else if (preg_match('/^[a-zA-Z0-9]{3,5}$/', $input)) {
            $this->symbol = $input;
        }
    }

    public function getId(): int
    {
        if (isset($this->id)) {
            return $this->id;
        }

        if (!isset($this->symbol)) {
            return 0;
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

    public function isOpen(): bool
    {
        $query = "SELECT
                      timezone,
                      open,
                      close
                  FROM stock_markets
                  JOIN stock_exchanges USING (exchange_id)
                  WHERE market_id = ?";

        $values[] = $this->getId();

        $stmt = $this->conn->runQuery($query, $values);
        $stmt->bind_result($timezone, $open, $close);
        $stmt->fetch();

        $tz_open = new \DateTime($open, new \DateTimeZone($timezone));
        $tz_close = new \DateTime($close, new \DateTimeZone($timezone));
        $now = new \DateTime();

        if ($now >= $tz_open && $now <= $tz_close && $now->format('N') < 6) {
            return true;
        }

        return false;
    }

    public function getConstituents(): object
    {
        $query = "SELECT
                      stock_id,
                      stock_symbol,
                      stock_name,
                      stock_currency
                  FROM stock_market_constituents
                  JOIN stock_symbol_list USING (stock_id)
                  JOIN stock_markets USING (market_id)
                  WHERE market_symbol = ?";

        $values[] = $this->getSymbol();

        $stmt = $this->conn->runQuery($query, $values);   

        return $stmt;
    }

    public function getPrices(): object
    {
        $query = 'SELECT
                      market_id,
                      date,
                      price
                  FROM stock_market_prices
                  WHERE market_id = ?';

        $values[] = $this->getId();

        $stmt = $this->conn->runQuery($query, $values);   

        return $stmt;
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
                      exchange_suffix,
                      timezone,
                      open,
                      close,
                      price
                  FROM stock_markets
                  JOIN stock_exchanges USING (exchange_id)
                  LEFT JOIN (
                      SELECT
                          market_id,
                          MAX(date) AS date
                      FROM stock_market_prices
                      GROUP BY market_id
                  ) t1 USING (market_id)
                  LEFT JOIN stock_market_prices USING (market_id, date)";

        $stmt = $conn->runQuery($query);

        return $stmt;
    }
}

