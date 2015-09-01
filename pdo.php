<?php

require_once __DIR__ . '/func.php';

$configFile = __DIR__ . '/config.ini';

// filter for environmental vars
$envvars = array_filter($_SERVER, function($key) {
    return 'RDS_' === substr($key, 0, 4);
}, ARRAY_FILTER_USE_KEY);

// if none, fallback to config.ini
if (empty($envvars)) {

    if (!is_file($configFile) || !is_readable($configFile)) {
        header('HTTP/1.1 500 Internal Server Error');
        exit('Sorry! There was an error.');
    }

    $config = parse_ini_file($configFile);

    // load with config
    $db = dbInit(
        $config['host'],
        $config['name'],
        $config['user'],
        $config['pass']
    );

} else {
    // load with envvars
    $db = dbInit(
        $_SERVER['RDS_HOSTNAME'],
        $_SERVER['RDS_DB_NAME'],
        $_SERVER['RDS_USERNAME'],
        $_SERVER['RDS_PASSWORD']
    );
}
