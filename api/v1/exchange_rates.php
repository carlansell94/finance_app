<?php

namespace Finance\api\v1;

use Finance\core\{Currency, DataError, ExchangeRate};

require __DIR__ . '/../../autoloader.php';

header('Content-Type: application/json');

$uri = str_replace('/finance/api/v1/exchange_rates/', '', $_SERVER['REQUEST_URI']);

if (substr($uri, 0, 1) != '?') {
    $uri = strtok($uri, '?');
}

$request_path = explode('/', $uri);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = getExchangeRates($request_path);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = addExchangeRate($request_path);
}

if (isset($result['errors'])) {
    http_response_code(422);
}

$result = json_encode($result, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
echo $result;


function getExchangeRates($request_path)
{
    $result['errors'][] = "Invalid request URI";

    if ($request_path[0] == '' || substr($request_path[0], 0, 1) == '?') {
        $result = getAllExchangeRates();
    }

    if (is_numeric($request_path[0])) {
        $result = getExchangeRatesById($request_path);
    }

    if (preg_match('/^(\d{4}(?:\-\d{2}){2})$/', $request_path[0])) {
        $result = getExchangeRatesByDate($request_path);
    }

    if (preg_match('/^[a-zA-Z]{3}-[a-zA-Z]{3}$/', $request_path[0])) {
        $result = getExchangeRatesPair($request_path);
    }

    if (preg_match('/^[a-zA-Z]{3}$/', $request_path[0])) {
        $result = getExchangeRatesSingle($request_path);
    }

    if ($request_path[0] == "summary") {
        $result = getExchangeRatesSummary($request_path);
    }

    return $result;
}


function getAllExchangeRates()
{
    $params = null;
    $values = null;
    $result = areDateParamsValid();

    if (isset($result['errors'])) {
        return $result;
    }

    if (isset($_GET['start_date'])) {
        $params[] = "date >= ?";
        $values[] = $_GET['start_date'];
    }

    if (isset($_GET['end_date'])) {
        $params[] = "date <= ?";
        $values[] = $_GET['end_date'];
    }

    $stmt = ExchangeRate::getRates($params, $values);
    $stmt->bind_result($date, $currency_1, $currency_2, $rate);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $currencies = $currency_1 . "-" . $currency_2;
        $result_data['currency_1'] = $currency_1;
        $result_data['currency_2'] = $currency_2;
        $result_data['rates'][$date] = $rate;

        $result[$currencies] = $result_data;
    }

    $res['data'] = array_values($result);

    return $res;
}


function getExchangeRatesById($request_path)
{
    $result = areDateParamsValid();

    if (isset($request_path[1])) {
        $result['errors'][] = "Invalid request URI";
    }

    if (isset($result['errors'])) {
        return $result;
    }

    $params[] = "currency_rate_id = ?";
    $values[] = $request_path[0];

    $stmt = ExchangeRate::getRates($params, $values);
    $stmt->bind_result($date, $currency_1, $currency_2, $rate);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['currency_1'] = $currency_1;
        $result_data['currency_2'] = $currency_2;
        $result_data['date'] = $date;
        $result_data['rate'] = $rate;

        $result['data'][$request_path[0]] = $result_data;
    }

    if (!isset($result['data'])) {
        $result['errors'][] = "Invalid exchange rate id";
    }

    return $result;
}


function getExchangeRatesByDate($request_path)
{
    $result = areDateParamsValid($request_path[0]);

    if (isset($request_path[1]) || isset($result['errors'])) {
        $result['errors'][0] = "Invalid request URI";
        return $result;
    }

    $params[] = "date = ?";
    $values[] = $request_path[0];

    $stmt = ExchangeRate::getRates($params, $values);
    $stmt->bind_result($date, $currency_1, $currency_2, $rate);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['currency_1'] = $currency_1;
        $result_data['currency_2'] = $currency_2;
        $result_data['date'] = $date;
        $result_data['rate'] = $rate;

        $result['data'][] = $result_data;
    }

    return $result;
}


function getExchangeRatesPair($request_path) {
    $params = null;
    $values = null;
    $result = areDateParamsValid();

    if (isset($request_path[1]) && substr($request_path[1], 0, 1) != '?') {
        $result['errors'][] = "Invalid request URI";
    }

    $currencies = explode('-', $request_path[0]);

    $currency_1_id = new Currency($currencies[0]);
    $currency_1_id = $currency_1_id->getId();
    $currency_2_id = new Currency($currencies[1]);
    $currency_2_id = $currency_2_id->getId();

    if (!$currency_1_id || !$currency_2_id || $currency_1_id == $currency_2_id) {
        $result['errors'][] = "Invalid currency pair";
    } else {
        $params[] = "currency_1 = ?";
        $params[] = "currency_2 = ?";
        $values[] = $currency_1_id;
        $values[] = $currency_2_id;
    }

    if (isset($result['errors'])) {
        return $result;
    }

    if (isset($_GET['start_date'])) {
        $params[] = "date >= ?";
        $values[] = $_GET['start_date'];
    }

    if (isset($_GET['end_date'])) {
        $params[] = "date <= ?";
        $values[] = $_GET['end_date'];
    }

    $stmt = ExchangeRate::getRates($params, $values);
    $stmt->bind_result($date, $currency_1, $currency_2, $rate);
    $stmt->store_result();

    $result_data['currency_1'] = $currencies[0];
    $result_data['currency_2'] = $currencies[1];

    while ($stmt->fetch()) {
        $result_data['rates'][$date] = $rate;
    }

    $result['data'][] = $result_data;

    return $result;
}


function getExchangeRatesSingle($request_path)
{
    $params = null;
    $values = null;
    $result = areDateParamsValid();

    if (isset($request_path[1]) && substr($request_path[0], 0, 1) != '?') {
        $result['errors'][] = "Invalid request URI";
    }

    $currency = new Currency($request_path[0]);
    $currency_id = $currency->getId();

    if (!$currency_id) {
        $result['errors'][] = "Invalid currency";
    }

    if (isset($result['errors'])) {
        return $result;
    }

    if (isset($_GET['start_date'])) {
        $params[] = "t1.date >= ?";
        $values[] = $_GET['start_date'];
    }

    if (isset($_GET['end_date'])) {
        $params[] = "t1.date <= ?";
        $values[] = $_GET['end_date'];
    }

    if (!isset($result['errors'])) {
        $stmt = ExchangeRate::getCurrencyRates($currency, $params, $values);
        $stmt->bind_result($date, $currency_2, $from, $to, $flag);
        $stmt->store_result();

        while ($stmt->fetch()) {
            $result_data['currency'] = $currency_2;
            $result_data['flag'] = base64_encode($flag);
            $result_data['rates'][$date]['from'] = $from;
            $result_data['rates'][$date]['to'] = $to;

            $result[$currency_2] = $result_data;
        }
    }

    $res['data'] = array_values($result);

    return $res;
}


function getExchangeRatesSummary($request_path) {
    $params = null;
    $result = null;

    if (isset($request_path[1])) {
        if ($request_path[1] == 'major') {
            $params[] = "ranking_id = 1";
            $params[] = "ranking_id_2 = 1";
        } else {
            $result['errors'][] = "Invalid request URI";
            return $result;
        }
    }

    $stmt = ExchangeRate::getChange($params);
    $stmt->bind_result($currency_1, $currency_2, $date_old, $rate_old, $date, $rate);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['currency_1'] = $currency_1;
        $result_data['currency_2'] = $currency_2;
        $result_data['date'] = $date;
        $result_data['rate'] = $rate;
        $result_data['change']['value'] = $rate - $rate_old;
        $result_data['change']['percent'] = ($rate / $rate_old - 1) * 100;

        $result['data'][] = $result_data;
    }

    return $result;
}


function addExchangeRate() {
    $input = file_get_contents('php://input');
    $json = json_decode($input, true);

    if (count($json) > 1) {
        $error = "More than one input provided";
        $result['errors'][] = $error;
        new DataError(__FILE__, __FUNCTION__, $json, $error);

        return $result;
    }

    $currency_1 = new Currency($input['currency_1']);
    $currency_1_id = $currency_1->getId();
    $currency_2 = new Currency($input['currency_2']);
    $currency_2_id = $currency_2->getId();

    if (!$currency_1_id || !$currency_2_id) {
        $error = "Incorrect currency parameter";
        $result['errors'][] = $error;
        new DataError(__FILE__, __FUNCTION__, $input, $error);

        return $result;
    }

    $exchange_rate = ExchangeRate();
    $exchange_rate->setCurrency1($currency_1)
                  ->setCurrency2($currency_2)
                  ->setDate($input['date'])
                  ->setRate($input['rate']);

    try {
        $exchange_rate->create();
    } catch (DBException $e) {
        $result['errors'][] = "{$e->getMessage()}";
    }

    return $result;
}


function areDateParamsValid($date_1 = null, $date_2 = null)
{
    $result = array();
    $now = new \DateTime();
    $start_date = null;
    $end_date = null;

    if (isset($_GET['start_date'])) {
        $date_1 = $_GET['start_date'];
    }

    if (isset($_GET['end_date'])) {
        $date_2 = $_GET['end_date'];
    }

    $dateCheck = function ($date) {
        $dt = \DateTime::createFromFormat('Y-m-d', $date);

        if ($dt || $dt->format('Y-m-d') === $date) {
            return true;
        }

        return false;
    };

    if ($date_1) {
        $dt = \DateTime::createFromFormat('!Y-m-d', $date_1);

        if (!$dt || !($dt->format('Y-m-d') === $date_1)) {
            $result['errors'][] = "Invalid end date";
        } else {
            $start_date = $dt;
        }
    }

    if ($date_2) {
        $dt = \DateTime::createFromFormat('!Y-m-d', $date_2);

        if (!$dt || !($dt->format('Y-m-d') === $date_2)) {
            $result['errors'][] = "Invalid end date";
        } else {
            $end_date = $dt;
        }
    }

    if ($start_date && $end_date && $start_date > $end_date) {
        $result['errors'][] = "Start date is later than end date";
    }

    if ($start_date && $start_date > $now) {
        $result['errors'][] = "Start date is in the future";
    }

    return $result;
}

