<?php

namespace Finance\sync;

use Finance\core\Stock;

require_once __DIR__ . '/../autoloader.php';

abstract class StockPriceSync extends Sync
{
    protected $stock;

    public function setStock(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function isMarketOpen(): bool
    {
        if ($this->stock->getMarket() !== null) {
            return $this->stock->getMarket()->isOpen();
        }
        
        return false;
    }

    public function setStartDate()
    {
        $query = "SELECT MAX(date) + INTERVAL 1 DAY as date
                  FROM stock_prices
                  WHERE stock_id = ?";

        $params[] = $this->stock->getId();

        $stmt = $this->conn->runQuery($query, $params);
        $stmt->bind_result($date);
        $stmt->fetch();

        if (!$date) {
            $this->start_date = new \DateTime(SYNC_START_DATE);
        } else {
            $this->start_date = new \DateTime($date);
        }
    }

    public function modifyEndDate($modifier)
    {
        $this->end_date->modify($modifier);
    }
}

