<?php

namespace Finance\config;

require_once __DIR__ . '/../autoloader.php';

final class Config
{
    private $config;

    public function setValues(array $config)
    {
        $this->config = $config;
    }

    public function isValid()
    {
        if ($this->config === null) {
            return false;
        }

        @$conn = new \mysqli($this->config['DB_HOST'], $this->config['DB_USER'], $this->config['DB_PASS'], $this->config['DB_NAME']);

        if ($conn->connect_error) {
            return false;
        }

        return true;
    }

    public function store()
    {
        $settings = null;
        $out = null;

        foreach ($this->config as $key => $value) {
            $settings[explode('_', $key)[0]][$key] = $value;
        }

        foreach ($settings as $key => $setting) {
            $out .= "[$key]\n";

            foreach ($setting as $name =>$value) {
                $out .= $name . ' = "' . \str_replace('"', '\"', $value) . "\"\n";
            }

            $out .= "\n";
        }

        return file_put_contents(__DIR__ . '/config.ini', $out);
    }

    public function remove()
    {
        unlink(__DIR__ . '/config.ini');
    }
}

