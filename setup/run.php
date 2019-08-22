<?php

require_once __DIR__ . '/../autoloader.php';

use Finance\config\Config;

http_response_code(422);

$settings = array(
                 'DB_HOST' => $_POST['hostname'],
                 'DB_NAME' => $_POST['database'],
                 'DB_USER' => $_POST['username'],
                 'DB_PASS' => $_POST['password'],
                 'SYNC_START_DATE' => $_POST['start_date']
             );

$config = new Config();
$config->setValues($settings);

if (!$config->isValid()) {
    echo "Error connecting to database, please check your settings.";
    return;
}

$schemas[] = 'schema/tables.sql';

if (isset($_POST['install_data'])) {
    $schemas[] = 'schema/data.sql';
}

foreach ($schemas as $schema) {
    $db_setup = "mysql --user={$settings['DB_USER']} --password=\"" . $settings['DB_PASS']
              . "\" -h {$settings['DB_HOST']} -D {$settings['DB_NAME']} -e 'SOURCE $schema' 2>&1";

    exec($db_setup, $errors);

    if (isset($errors[0])) {
        echo "Error installing database, ensure the schema files are present in /setup/schema";
        return;
    }
}

if (!$config->store()) {
    echo "Error storing configuration file, please check permissions on /config are set correctly.";
    return;
}

http_response_code(200);

echo "Setup has completed successfully.";

