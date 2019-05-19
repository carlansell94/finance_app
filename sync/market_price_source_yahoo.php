<?php

namespace Finance\sync;

use Finance\core\{DbException, MarketPrice};

require_once __DIR__ . '/../autoloader.php';

final class MarketPriceSourceYahoo extends MarketPriceSync implements IMarketPriceSync
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
        $symbol = $this->market->getSymbol();

        if ($symbol == "AIM1") {
            $this->sync_symbol = $symbol . ".L";
        } else {
            $this->sync_symbol = "^" . $symbol;
        }
    }

    private function setURL()
    {
        $base_url = "https://query2.finance.yahoo.com/v8/finance/chart/";
        $start_date = $this->start_date->format('U');
        $end_date = $this->end_date->format('U') + 86499;
        $params = "&period1=" . $start_date . "&period2=" . $end_date . "&interval=1d";

        $this->url = $base_url . $this->sync_symbol . "?symbol=" . $this->sync_symbol . $params;
    }

    private function fetchPrices()
    {
        $json = json_decode(file_get_contents($this->url), true);
        $json_base = $json['chart']['result'][0];

        if (!isset($json_base['timestamp'])) {
            return;
        }

        $price_dates = $json_base['timestamp'];
        $price_data = $json_base['indicators']['quote'][0];
        $price_currency = $json_base['meta']['currency'];
        $i = 0;

        $this->prices = array();

        foreach ($price_dates as $date) {
            $insert_date = gmdate('Y-m-d', $date);

            if ($insert_date < $this->start_date->format('Y-m-d')) {
                continue;
            }

            $this->prices[$insert_date] = $price_data['close'][$i];
            $i++;
        }
    }

    private function storePrices()
    {
        foreach ($this->prices as $date => $price) {
            $stock_price = new MarketPrice($this->market);
            $stock_price->setPrice($date, $price);

            try {
                $stock_price->create();
            } catch (DbException $e) {
                // Do nothing, throwing DBException logs the issue
            }
        }
    }
}

