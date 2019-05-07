<?php

namespace Finance\sync;

use Finance\core\{Currency, ExchangeRate};

require_once __DIR__ . '/../autoloader.php';

final class ExchangeRateSourceXe extends ExchangeRateSync implements IExchangeRateSync
{
    private $currency_list;
    private $fetch_date;
    private $url;
    private $content;

    public function run()
    {
        $this->getLastSyncDates();

        $this->fetch_date = $this->start_date->modify('-1 day');

        for ($this->fetch_date; $this->fetch_date <= $this->end_date; $this->fetch_date->modify('+1 day')) {
            $this->setUrl()
                 ->fetchContent()
                 ->storeRates();
        }
    }

    private function getLastSyncDates()
    {
        $stmt = Currency::getLastSyncDates($this->currency);
        $stmt->bind_result($currency, $date);

        while ($stmt->fetch()) {
            if (isset($date)) {
                $this->currency_list[$currency] = \DateTime::createFromFormat('!Y-m-d', $date);
            } else {
                $this->currency_list[$currency] = new \DateTime(SYNC_START_DATE);
            }
        }
    }

    private function setUrl()
    {
        $base_url = "https://www.xe.com/currencytables/";
        $currency = $this->currency->getIsoCode();

        $this->url = $base_url . "?from=" . $currency . "&date=" . $this->fetch_date->format('Y-m-d');

        return $this;
    }

    private function fetchContent()
    {
        libxml_use_internal_errors(true);

        $this->content = new \DOMDocument();
        $this->content->loadHTMLFile($this->url);
        $this->content = $this->content->getElementsByTagName('tbody')->item(0);

        return $this;
    }

    private function storeRates()
    {
        foreach ($this->content->getElementsByTagName('tr') as $row) {
            $field = $row->getElementsByTagName('td');
            $currency = $field->item(0)->nodeValue;
            $rate = $field->item(2)->nodeValue;

            if (!isset($this->currency_list[$currency])) {
                continue;
            }

            $currency_date = $this->currency_list[$currency] ?? null;
            $currency_2 = new Currency($currency);

            $er = new ExchangeRate();

            $er->setCurrency1($this->currency)
               ->setCurrency2($currency_2)
               ->setDate($this->fetch_date)
               ->setRate($rate);

            try {
                if ($this->fetch_date > $currency_date || $currency_date === null) {
                    $er->create();
                } else if ($this->fetch_date == $currency_date) {
                    $er->update();
                }
            } catch (DBException $e) {
                // Do nothing, throwing DBException logs the issue
            }
        }
    }
}

