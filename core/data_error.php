<?php

namespace Finance\core;

require __DIR__ . '/../autoloader.php';

final class DataError extends Issue
{
    public function __construct(string $file, string $function_name, array $params, string $message)
    {
        parent::__construct();
        $this->setFile($file);
        $this->setParams($params);

        $this->issue_type = 2;        
        $this->function_name = $function_name;
        $this->message = $message;

        $this->create();
    }
}

