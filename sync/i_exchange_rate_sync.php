<?php

namespace Finance\sync;

require __DIR__ . '/../autoloader.php';

interface IExchangeRateSync
{   
    public function run();
}

