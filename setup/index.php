<?php

if (file_exists(__DIR__ . '/../config/config.ini') && !isset($_GET['force'])) {
    require 'complete.php';
} else {
    require 'setup.php';
}

