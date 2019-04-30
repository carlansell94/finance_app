<?php

final class AutoLoader
{
    public static function getClass($class)
    {
        $path = explode('\\', $class);

        if ($path[0] === "Finance") {
            array_shift($path);
        }

        $file_to_include = null;

        foreach ($path as $part) {
            $part = preg_replace('/(?<!^)([A-Z])/', '_\\1', $part);
            $file_to_include .= '/' . strtolower($part);
        }

        $file_to_include .= '.php';

        if (file_exists(__DIR__ . $file_to_include)) {
            require __DIR__ . $file_to_include;
        }
    }
}

spl_autoload_register('AutoLoader::getClass');

