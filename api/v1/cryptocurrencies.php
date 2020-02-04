<?php

namespace Finance\api\v1;

use Finance\model\Cryptocurrency;

require_once __DIR__ . '/../../autoloader.php';

header('Content-Type: application/json');

$uri = str_replace('/finance/api/v1/cryptocurrencies', '', $_SERVER['REQUEST_URI']);

$request_path = explode('/', $uri);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = getCryptocurrencies($request_path);
}

if (isset($result['errors'])) {
    http_response_code(422);
}

$result = json_encode($result, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

echo $result;


function getCryptocurrencies($request_path)
{
    $params = null;
    $result = null;

    if (isset($request_path[1]) && $request_path[1] != '') {
        $result['errors'][] = "Invalid request URI";

        return $result;
    }

    $model = new Cryptocurrency;
    $cryptocurrencies = $model->get();

    while ($cryptocurrency = $cryptocurrencies->fetch_object("\Finance\core\Cryptocurrency")) {
        $result_data['symbol'] = $cryptocurrency->symbol;
        $result_data['name'] = $cryptocurrency->name;
        $result_data['icon'] = $cryptocurrency->getIcon();
        $result_data['creation_date'] = $cryptocurrency->creation_date;

        $result['data'][] = $result_data;
    }

    return $result;
}

