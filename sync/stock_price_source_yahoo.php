<?php

namespace Finance\sync;

use Finance\core\{DbException, DataError, StockPrice};

require_once __DIR__ . '/../autoloader.php';

class StockPriceSourceYahoo extends StockPriceSync
{
    private $prices;
    private $sync_symbol;
    private $url;

    public function run()
    {
        $this->setSyncSymbol();
        $this->setURL();
        $this->fetchPrices();
        $this->storePrices();
    }

    private function setSyncSymbol()
    {
        $symbol = str_replace('.', '-', $this->stock->getSymbol());

        if ($symbol != 'DBK') {
            $symbol = $symbol . '.' . 'L';
        } else {
            $symbol = $symbol . '.' . 'DE';
        }

        $this->sync_symbol = str_replace('-.', '.', $symbol);
    }

    private function setURL()
    {
        $base_url = "https://query2.finance.yahoo.com/v8/finance/chart/";
        $start_date = $this->start_date->format('U');
        $end_date = $this->end_date->format('U') + 86400;

        $this->url = "{$base_url}{$this->sync_symbol}?symbol={$this->sync_symbol}&period1=$start_date&period2=$end_date&interval=1d";
    }

    private function fetchPrices()
    {
        $json = @json_decode(file_get_contents($this->url), true);

        if ($json === null) {
            try {
                $params = array(
                              "start_date" => $this->start_date->format('Y-m-d'),
                              "end_date" => $this->end_date->format('Y-m-d'),
                              "symbol" => $this->sync_symbol
                          );

                throw new DataError(__FILE__, __FUNCTION__, $params, "Error fetching data");
            } catch (DataError $e) {
                // Do nothing, throwing DataError logs the issue
            }

            return;
        }

        $json_base = $json['chart']['result'][0];

        if (!isset($json_base['timestamp'])) {
            return;
        }

        $price_data = $json_base['indicators']['quote'][0];
        $price_dates = $json_base['timestamp'];
        $price_currency = $json_base['meta']['currency'];
    
        $i = 0;
        $multiplier = 1;

        if ($price_currency != 'GBp') {
            $multiplier = 100;
        }

        foreach ($price_dates as $date) {
            $insert_date = gmdate('Y-m-d', $date);
            $date_check = strtotime($insert_date . 'UTC') + 86399;

            if ($date < $date_check && $date >= $this->start_date->format('U')) {
                $this->prices[$insert_date] = array(
                    $price_data['high'][$i] * $multiplier,
                    $price_data['low'][$i] * $multiplier,
                    $price_data['close'][$i] * $multiplier
                );

                $i++;
            }
        }
    }

    private function storePrices()
    {
        foreach ((array) $this->prices as $date => $prices) {
            $stock_price = new StockPrice($this->stock);
            $stock_price->setPrice($date, $prices[0], $prices[1], $prices[2]);

            try {
                $stock_price->create();
            } catch (DbException $e) {
                // Do nothing, throwing DBException logs the issue
            }
        }
    }
}

