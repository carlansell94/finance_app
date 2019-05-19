<?php

namespace Finance\sync;

require_once __DIR__ . '/../autoloader.php';

interface IMarketPriceSync
{   
    public function run();
}

