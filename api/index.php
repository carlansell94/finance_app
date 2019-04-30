<?php

$currencies['data'][0]['iso_code'] = "GBP";
$currencies['data'][0]['name'] = "Great British Pound";
$currencies['data'][0]['ranking'] = "1";
$currencies['data'][0]['flag'] = "(base64_image_string)";
$currencies['data'][0]['symbol'] = "\u00a3";
$currencies['data'][0]['symbol_minor'] = "p";
$currencies = json_encode($currencies, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

$er_add['data'][0]['date'] = "date";
$er_add['data'][0]['currency_1'] = "currency_1";
$er_add['data'][0]['currency_2'] = "currency_2";
$er_add['data'][0]['rate'] = "rate";
$er_add = json_encode($er_add, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

$core['data'][0]['currency_1'] = "currency_1";
$core['data'][0]['currency_2'] = "currency_2";
$core['data'][0]['rates']['date_1'] = "rate";
$core['data'][0]['rates']['date_2'] = "rate";
$core = json_encode($core, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

$er_id['data']['ID']['currency_1'] = "currency_1";
$er_id['data']['ID']['currency_2'] = "currency_2";
$er_id['data']['ID']['date'] = "date";
$er_id['data']['ID']['rate'] = "rate";
$er_id = json_encode($er_id, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

$er_date['data'][0]['currency_1'] = "currency_1";
$er_date['data'][0]['currency_2'] = "currency_2";
$er_date['data'][0]['date'] = "DATE";
$er_date['data'][0]['rate'] = "rate";
$er_date = json_encode($er_date, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

$er_currency['data'][0]['currency'] = "currency";
$er_currency['data'][0]['rates']['date']['from'] = "from CUR to currency";
$er_currency['data'][0]['rates']['date']['to'] = "from currency to CUR";
$er_currency = json_encode($er_currency, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

$er_summary['data'][0]['currency_1'] = "currency_1";
$er_summary['data'][0]['currency_2'] = "currency_2";
$er_summary['data'][0]['date'] = "date";
$er_summary['data'][0]['rate'] = "rate";
$er_summary['data'][0]['change']['value'] = "value";
$er_summary['data'][0]['change']['percent'] = "percent";
$er_summary = json_encode($er_summary, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0">
    <base href="/finance/" target="_blank">
    <link rel="stylesheet" type="text/css" href="css/api.css">
	<title>Finance App - API</title>
</head>
<h1>v1</h1>
<h2>Currencies</h2>
<table>
    <tr>
        <th>URI
        <th>Header
        <th>Description
        <th>Optional Parameters
        <th>Return JSON
    <tr>
        <td>currencies
        <td>GET
        <td>Retrieve all currencies tracked by the application.
        <td>
        <td><pre><?= $currencies; ?></pre>
</table>
<h2>Exchange Rates</h2>
<table>
    <tr>
        <th>URI
        <th>Header
        <th>Description
        <th>Optional Parameters
        <th>JSON
    <tr>
        <td>exchange_rates
        <td>GET
        <td>Retrieve exchange rates for all currency pairs.
        <td><ul><li>start_date<li>end_date</ul>
        <td><pre><?= $core; ?></pre>
    <tr>
        <td>exchange_rates
        <td>POST
        <td>Add a single exchange rate with the data provided.
        <td><ul><li>start_date<li>end_date</ul>
        <td><pre><?= $er_add; ?></pre>
    <tr>
        <td>exchange_rates/{ID}
        <td>GET
        <td>Retrieve the exchange rate with the provided ID.
        <td>
        <td><pre><?= $er_id; ?></pre>
    <tr>
        <td>exchange_rates/{DATE}
        <td>GET
        <td>Retrieve all exchange rates for the provided date,
        <td>
        <td><pre><?= $er_date; ?></pre>
    <tr>
        <td>exchange_rates/{CUR}
        <td>GET
        <td>Retrieve exchange rates for the provided currency.
        <td><ul><li>start_date<li>end_date</ul>
        <td><pre><?= $er_currency; ?></pre>
    <tr>
        <td>exchange_rates/{CUR-CUR}
        <td>GET
        <td>Retrieve exchange rates for the provided currency pair.
        <td><ul><li>start_date<li>end_date</ul>
        <td><pre><?= $core; ?></pre>
    <tr>
        <td>exchange_rates/summary
        <td>GET
        <td>Retrieve the most recent exchange rate of a currency pair, and the change from the previous day.
        <td>
        <td><pre><?= $er_summary; ?></pre>
    <tr>
        <td>exchange_rates/summary/major
        <td>GET
        <td>Retrieve the most recent exchange rate of a currency pair, and the change from the previous day. Only returns rank 1 currencies (GBP, EUR, USD).
        <td>
        <td><pre><?= $er_summary; ?></pre>
</table>
