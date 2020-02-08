<?php

namespace Finance\model;

use Finance\core\{Connection, Market};

class MarketPrice
{
    public function __construct() {
        $this->conn = Connection::instance();
    }

    public function setMarket(Market $market): MarketPrice {
        $this->market = $market;

        return $this;
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
                      $this->market->market_id,
                      $this->date,
                      $this->price
                  );

        $result = $this->conn->runQuery($query, $values);

        return $result;
    }
}

