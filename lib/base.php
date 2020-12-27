<?php

/**
 * Этот файл предназначен для включения в те, где нам нужно что-бы то не было.
 */
require_once('vendor/autoload.php');
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../functions.php');
require_once(__DIR__ . '/../helpers.php');
require_once(__DIR__ . '/../smtp.php');
require_once(__DIR__ . '/../src/user/user.php');

session_start();

$settings = require_once(__DIR__ . '/../src/settings/settings.php');
$connection = db_connect(
    $settings['mysql']['host'] . ':' . $settings['mysql']['port'],
    $settings['mysql']['user'],
    $settings['mysql']['password'],
    $settings['mysql']['database']
);
