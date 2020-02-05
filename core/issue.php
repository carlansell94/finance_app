<?php

namespace Finance\core;

require_once __DIR__ . '/../autoloader.php';

abstract class Issue extends \Exception
{
    protected $conn;
    protected $issue_id;
    protected $issue_type;
    protected $function_name;
    protected $params;
    protected $issue_status;

    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function setFile(string $path): Issue
    {
        $this->file = basename($path);
        return $this;
    }

    public function setParams(array $params = null): Issue
    {
        $this->params = json_encode($params, JSON_FORCE_OBJECT);
        return $this;
    }

    public function create()
    {
        $query = 'INSERT INTO issue_tracker (
                      issue_type,
                      file,
                      function,
                      params,
                      message
                  )
                  VALUES (?, ?, ?, ?, ?)';

        $params = array(
                        $this->issue_type,
                        $this->file,
                        $this->function_name,
                        $this->params,
                        $this->message
                  );

        $this->conn->runQuery($query, $params);
    }
}

