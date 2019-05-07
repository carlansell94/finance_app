<?php

namespace Finance\api\v1;

use Finance\core\Currency;

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

    $stmt = Currency::getFullList();
    $stmt->bind_result($iso_code, $name, $ranking, $flag, $symbol, $symbol_minor);
    $stmt->store_result();

    while ($stmt->fetch()) {
        $result_data['iso_code'] = $iso_code;
        $result_data['name'] = $name;
        $result_data['ranking'] = $ranking;
        $result_data['flag'] = base64_encode($flag);
        $result_data['symbol'] = $symbol;
        $result_data['symbol_minor'] = $symbol_minor;

        $result['data'][] = $result_data;
    }

    return $result;
}

