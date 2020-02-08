<?php

namespace Finance\sync\MarketPrices;

use Finance\core\DataError;
use Finance\model\MarketPrice;

final class MarketPriceSourceYahoo extends MarketPriceSync
{
    public function run()
    {
        foreach ($this->markets as $market) {
            $this->start_date = new \DateTime($market->date);
            $end_date = $this->end_date;

            if ($market->isOpen()) {
                $this->modifyEndDate('-1 day');
            }

            if (!$this->isRequired()) {
                $this->end_date = $end_date;
                continue;
            }

            try {
                $market->sync_symbol = $this->getSyncSymbol($market);
                $content = $this->getContent($market);
                $prices = $this->parseContent($content);
                $this->storePrices($market, $prices);
            } catch (DataError $e) {
                // Do nothing, throwing DataError logs the issue
            }

            $this->end_date = $end_date;
        }
    }

    private function getSyncSymbol(\Finance\core\Market $market)
    {
        $symbol = $market->market_symbol;

        $symbol == "AIM1" ? $symbol .= ".L" : $symbol = "^" . $symbol;

        return $symbol;
    }

    private function getContent($market)
    {
        $params = array(
                      "symbol"   => $market->sync_symbol,
                      "period1"  => $this->start_date->format('U'),
                      "period2"  => $this->end_date->format('U') + 86499,
                      "interval" => "1d"
                  );

        $request = "https://query2.finance.yahoo.com/v8/finance/chart/?" . http_build_query($params);

        $ch = curl_init($request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        $content = curl_exec($ch);

        if ($content === null) {
            throw new DataError(__FILE__, __FUNCTION__, $params, curl_error($ch));
        }

        return $content;
    }

    private function parseContent($content): array
    {
        $json = json_decode($content);
        $json_base = $json->chart->result[0];

        if (!isset($json_base->timestamp)) {
            return false;
        }

        $price_dates = $json_base->timestamp;
        $price_data = $json_base->indicators->quote[0];
        $price_currency = $json_base->meta->currency;
        $i = 0;

        $prices = array();

        foreach ($price_dates as $date) {
            $insert_date = gmdate('Y-m-d', $date);
            $start_date = $this->start_date->format('Y-m-d');

            if ($insert_date < $start_date) {
                continue;
            }

            $prices[$insert_date] = $price_data->close[$i];
            $i++;
        }

        return $prices;
    }

    private function storePrices(\Finance\core\Market $market, array $prices)
    {
        $market_price = new MarketPrice();
        $market_price->setMarket($market);

        foreach ($prices as $date => $price) {
            $market_price->setPrice($date, $price);

            try {
                $market_price->create();
            } catch (DbException $e) {
                // Do nothing, throwing DBException logs the issue
            }
        }
    }
}

