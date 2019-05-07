<?php

namespace Finance\sync;

require_once __DIR__ . '/../autoloader.php';

interface IExchangeRateSync
{   
    public function run();
}

