<?php

if (file_exists(__DIR__ . '/../config/config.ini') && !isset($_GET['force'])) {
    require 'complete.html';
} else {
    require 'setup.html';
}

