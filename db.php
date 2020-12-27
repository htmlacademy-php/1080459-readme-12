<?php

/**
 * Подключается к БД
 *
 * @param  mixed $host Хост
 * @param  mixed $user Пользователь
 * @param  mixed $pass Пароль
 * @param  mixed $db   Название БД
 * @return void
 */
function db_connect($host, $user, $pass, $db)
{
    $con = mysqli_connect($host, $user, $pass, $db);
    if ($con == false) {
        $error = mysqli_connect_error();
        print($error);
        http_response_code(500);
        exit();
    }
    mysqli_set_charset($con, "utf8");
    return $con;
}
