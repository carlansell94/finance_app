<?php

namespace Finance\sync\MarketPrices;

use Finance\core\Market;
use Finance\sync\Sync;

abstract class MarketPriceSync extends Sync
{
    protected $markets;

    public function setMarkets(array $markets)
    {
        $this->markets = $markets;
    }

    public function setEndDate($date)
    {
        $this->end_date = $date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setStartDate()
    {
        foreach ($this->markets as $market) {
            if (!$market->date || $market->date < SYNC_START_DATE) {
                $this->start_date = new \DateTime(SYNC_START_DATE);
            } else {
                $this->start_date = new \DateTime($market->date);
            }
        }
    }

    public function modifyEndDate($modifier)
    {
        $this->end_date->modify($modifier);
    }
}

