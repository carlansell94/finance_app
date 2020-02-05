<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

final class DbException extends Issue
{
    public function __construct(\Exception $exception, array $params = null)
    {
        parent::__construct();
        $error = debug_backtrace(4)[3];

        $this->setFile($error['file']);
        $this->setParams($params);

        $this->issue_type = 1;
        $this->function_name = $error['function'];
        $this->message = $exception->getMessage();

        $this->create();
    }
}

