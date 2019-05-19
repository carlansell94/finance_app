<?php

namespace Finance\sync;

use Finance\core\Market;

require_once __DIR__ . '/../autoloader.php';

abstract class MarketPriceSync extends Sync
{
    protected $market;

    public function setMarket(Market $market)
    {
        $this->market = $market;
    }

    public function isMarketOpen(): bool
    {
        return $this->market->isOpen();
    }

    public function setStartDate()
    {
        $query = "SELECT MAX(date) + INTERVAL 1 DAY as date
                  FROM stock_market_prices
                  WHERE market_id = ?";

        $params[] = $this->market->getId();

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

