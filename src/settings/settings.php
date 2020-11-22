<?php
/**
 * Здесь мы заявим настройки. Так мы сможем, в случае необходимости переобъявить их и, если потребуется
 * мы сможем на разных компьютерах разработчиков и на прод/бета/стейдж/дев средах заявлять свои значения.
 * Обычно это делается через ENV-файлы, но так как вы не можете использовать подключаемые пакеты,
 * а писать это все самостоятельно будет затруднительно для начинающего, я воспользовался более примитивным
 * способом.
 */

$settings = null;
if (file_exists(__DIR__ . '/settings_local.php')) {
    $settings = require(__DIR__ . '/settings_local.php');
}

return $settings ?? [
    'mysql' => [
        'user' => 'root',
        'password' => '',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'readme'
    ],
    'smtp' => [
        'server' => 'phpdemo.ru',
        'port' => 25,
        'sender' => 'keks@phpdemo.ru',
        'user' => 'keks@phpdemo.ru',
        'password' => 'htmlacademy',
        'encryption' => false
    ],
    'site_name' => 'readme',
    'site_url' => 'readme.edu',
    'page_limit' => 6,
];


