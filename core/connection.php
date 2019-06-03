<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';
require_once __DIR__ . '/../config/db.php';

final class Connection implements IConnection
{
    protected static $instance;
    protected $conn;

    protected function __construct()
    {
        $this->conn = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public static function instance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function runQuery(string $query, array $param = null)
    {
        \mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);

        $stmt = $this->conn->prepare($query);

        if (isset($param)) {
            $stmt->bind_param(str_repeat('s', count($param)), ...$param);
        }

        try {
            $stmt->execute();
        } catch (\Exception $e) {
            throw new DbException($e, $param);
        }

        return $stmt;
    }
}

