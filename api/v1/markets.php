<?php

namespace Finance\api\v1;

use Finance\core\Market;

require_once __DIR__ . '/../../autoloader.php';

header('Content-Type: application/json');

$uri = str_replace('/finance/api/v1/markets', '', $_SERVER['REQUEST_URI']);

$request_path = explode('/', $uri);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($request_path[2]) && $request_path[2] == "constituents") {
        $result = getMarketConstituents($request_path);
    } else if (isset($request_path[1])) {
        $result = getMarketPrices($request_path);
    } else {
        $result = getMarkets($request_path);
    }
}

if (isset($result['errors'])) {
    http_response_code(422);
}

$result = json_encode($result, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

echo $result;


function getMarketConstituents($request_path)
{
    $params = null;
    $result = null;
    $market = new Market($request_path[1]);

    if ($market->getId() == 0) {
        $result['errors'][] = "Invalid market name";
    }

    if (isset($result['errors'])) {
        return $result;
    }

    $stmt = $market->getConstituents();
    $stmt->bind_result($id, $symbol, $name, $currency);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['stock_id'] = $id;
        $result_data['stock_symbol'] = $symbol;
        $result_data['stock_name'] = $name;
        $result_data['stock_currency'] = $currency;

        $result['data'][] = $result_data;
    }

    if (!isset($result['data'])) {
        $result['data'] = array();
    }

    if (isset($result['data'])) {
        $result['count'] = count($result['data']);
    }

    return $result;
}


function getMarketPrices($request_path)
{
    $params = null;
    $values = null;
    $market = new Market($request_path[1]);

    if ($market->getId() == 0) {
        $result['errors'][] = "Invalid market name";
    }

    if (isset($result['errors'])) {
        return $result;
    }
    
    $stmt = $market->getPrices();
    $stmt->bind_result($id, $date, $price);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['date'] = $date;
        $result_data['price'] = $price;

        $result['data'][] = $result_data;
    }

    return $result;
}


function getMarkets($request_path)
{
    $params = null;
    $result = null;

    if (isset($request_path[1]) && $request_path[1] != '') {
        $result['errors'][] = "Invalid request URI";

        return $result;
    }

    $stmt = Market::getFullList();
    $stmt->bind_result($id, $name, $symbol, $exchange_name, $exchange_symbol, $exchange_suffix, $timezone, $open, $close, $price);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['id'] = $id;
        $result_data['name'] = $name;
        $result_data['symbol'] = $symbol;
        $result_data['exchange_name'] = $exchange_name;
        $result_data['exchange_symbol'] = $exchange_symbol;

        if ($exchange_suffix !== null) {
            $result_data['exchange_suffix'] = $exchange_suffix;
        }

        $result_data['timezone'] = $timezone;
        $result_data['opening_time'] = $open;
        $result_data['closing_time'] = $close;
        $result_data['last_close'] = $price;

        $result['data'][] = $result_data;
    }

    return $result;
}

