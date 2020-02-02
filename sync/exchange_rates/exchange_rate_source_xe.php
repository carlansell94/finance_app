<?php

namespace Finance\sync\ExchangeRates;

use Finance\core\DataError;
use Finance\model\{Currency, ExchangeRate};

final class ExchangeRateSourceXe extends ExchangeRateSync
{
    private $fetch_date;
    private $content;

    public function run()
    {
        $this->fetch_date = $this->start_date;

        for ($this->fetch_date; $this->fetch_date <= $this->end_date; $this->fetch_date->modify('+1 day')) {
            $content = null;
            $targets = $this->getTargetCurrencies();

            if ($targets == "") {
                continue;
            }

            try {
                $content = $this->fetchContent($targets);
                $rates = $this->parseContent($content);
                $this->storeRates($rates);
            } catch (DataError $e) {
                // Do nothing, throwing DataError logs the issue
            }
        }
    }

    private function getTargetCurrencies(): string
    {
        $targets = array();

        foreach ($this->currency_list as $currency) {
            $date = new \DateTime($currency->date);

            if ($date < $this->fetch_date) {
                $targets[] = $currency->iso_code;
            }
        }

        return implode(",", $targets);
    }

    private function fetchContent(string $targets)
    {
        $params = array(
                      "from" => $this->base_currency->iso_code,
                      "date" => $this->fetch_date->format('Y-m-d'),
                  );

        $request = "https://www.xe.com/currencytables/?" . http_build_query($params);

        $ch = curl_init($request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        $content = curl_exec($ch);

        if ($content === null) {
            throw new DataError(__FILE__, __FUNCTION__, $params, curl_error($ch));
        }

        return $content;
    }

    private function parseContent(string $content): array
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($content);
        $result = array();
        
        $table = $dom->getElementsByTagName('tbody')->item(0);

        foreach ($table->getElementsByTagName('tr') as $row) {
            $field = $row->getElementsByTagName('td');
            $currency = $field->item(0)->nodeValue;
            $rate = $field->item(2)->nodeValue;

            if (!isset($this->currency_list[$currency])) {
                continue;
            }

            $result[$currency] = $rate;
        }

        return $result;
    }

    private function storeRates(array $rates)
    {
        foreach ($rates as $currency => $rate) {
            $er = new ExchangeRate();

            $er->setCurrency1($this->base_currency)
               ->setCurrency2($this->currency_list[$currency])
               ->setDate($this->fetch_date)
               ->setRate($rate);

            try {
                if ($this->fetch_date > $this->currency_list[$currency]->date) {
                    $er->create();
                } else if ($this->fetch_date == $this->currency_list[$currency]->date) {
                    $er->update();
                }
            } catch (DbException $e) {
                // Do nothing, throwing DBException logs the issue
            }
        }
    }
}

