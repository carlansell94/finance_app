<?php

namespace Finance\sync\ExchangeRates;

use Finance\core\Currency;
use Finance\sync\Sync;

abstract class ExchangeRateSync extends Sync
{
    public $base_currency;
    public $currency_list;

    public function setBaseCurrency(Currency $currency)
    {
        $this->base_currency = $currency;
    }

    public function setCurrencyList(array $currency_list)
    {
        $this->currency_list = $currency_list;
    }

    public function setStartDate()
    {
        $this->start_date = new \DateTime();

        foreach ($this->currency_list as $currency) {
            if ($currency->date < $this->start_date) {
                if ($currency->date > SYNC_START_DATE) {
                    $this->start_date = new \DateTime($currency->date);
                } else {
                    $this->start_date = new \DateTime(SYNC_START_DATE);
                }
            }
        }
    }
}

