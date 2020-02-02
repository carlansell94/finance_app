<?php

namespace Finance\api\v1;

use Finance\model\Currency;

require_once __DIR__ . '/../../autoloader.php';

header('Content-Type: application/json');

$uri = str_replace('/finance/api/v1/currencies', '', $_SERVER['REQUEST_URI']);

$request_path = explode('/', $uri);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = getCurrencies($request_path);
}

if (isset($result['errors'])) {
    http_response_code(422);
}

$result = json_encode($result, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

echo $result;


function getCurrencies($request_path)
{
    $params = null;
    $result = null;

    if (isset($request_path[1]) && $request_path[1] != '') {
        $result['errors'][] = "Invalid request URI";

        return $result;
    }

    $model = new \Finance\model\Currency;
    $currencies = $model->get();

    while ($currency = $currencies->fetch_object("\Finance\core\Currency")) {
        $result_data['iso_code'] = $currency->iso_code;
        $result_data['name'] = $currency->name;
        $result_data['ranking'] = $currency->ranking_id;
        $result_data['flag'] = $currency->getCountryFlag();
        $result_data['symbol'] = $currency->symbol;
        $result_data['symbol_minor'] = $currency->symbol_minor;

        $result['data'][] = $result_data;
    }

    return $result;
}

