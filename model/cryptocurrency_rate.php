<?php

namespace Finance\model;

use Finance\core\{Connection, Cryptocurrency, Currency};

final class CryptocurrencyRate
{
    private $conn;
    private $id;
    private $date;
    private $currency;
    private $cryptocurrency;
    private $rate;
    
    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function setDate($date): CryptocurrencyRate
    {
        if (is_string($date)) {
            $this->date = $date;
        } else {
            $this->date = $date->format('Y-m-d');
        }

        return $this;
    }

    public function setCurrency(Currency $currency): CryptocurrencyRate
    {
        $this->currency = $currency;

        return $this;
    }

    public function setCryptocurrency(Cryptocurrency $currency): CryptocurrencyRate
    {
        $this->cryptocurrency = $currency;

        return $this;
    }

    public function setRate($rate): CryptocurrencyRate
    {
        $this->rate = $rate;

        return $this;
    }

    public function create()
    {
        $query = "INSERT INTO cryptocurrency_rates (
                      date,
                      crypto_id,
                      currency_id,
                      rate
                  )
                  VALUES (?, ?, ?, ?)";

        $values = array(
                      $this->date,
                      $this->cryptocurrency->crypto_id,
                      $this->currency->currency_id,
                      $this->rate
                  );

        $result = $this->conn->runQuery($query, $values);

        return $result;
    }
}

