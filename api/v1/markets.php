<?php

namespace Finance\api\v1;

use Finance\core\Market;

require_once __DIR__ . '/../../autoloader.php';

header('Content-Type: application/json');

$uri = str_replace('/finance/api/v1/markets', '', $_SERVER['REQUEST_URI']);

$request_path = explode('/', $uri);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = getMarkets($request_path);
}

if (isset($result['errors'])) {
    http_response_code(422);
}

$result = json_encode($result, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

echo $result;


function getMarkets($request_path)
{
    $params = null;
    $result = null;

    if (isset($request_path[1]) && $request_path[1] != '') {
        $result['errors'][] = "Invalid request URI";

        return $result;
    }

    $stmt = Market::getFullList();
    $stmt->bind_result($id, $name, $symbol, $exchange_name, $exchange_symbol, $exchange_suffix);
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

        $result['data'][] = $result_data;
    }

    return $result;
}

