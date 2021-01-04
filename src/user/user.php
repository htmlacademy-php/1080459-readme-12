<?php

/**
 * Получает пользователя из $_SESSION
 *
 * @return array Пользователь
 */
function get_user(): ?array
{
    if ($_SESSION['is_auth'] !== 1) {
        return null;
    }
    $user = [];
    $user['id'] = $_SESSION['id'];
    $user['name'] = $_SESSION['username'];
    $user['avatar'] = $_SESSION['avatar'];
    return $user;
}

/**
*Возвращает подписан ли один пользователь на другого
*@param array $user_id Предполагаемый подписчик
*@param $author_id Предполагаемый пользователь, на которого подписаны
*@return bool Массив с данными пользователя
*
*/
function get_user_subscribed($connection, $user_id, $author_id): bool
{
    $select_subscribe_query = "SELECT * FROM subscribe WHERE follower_id = ? AND author_id = ?";
    $user_subscribed_mysqli = secure_query($connection, $select_subscribe_query, $user_id, $author_id);
    $subscribed = $user_subscribed_mysqli->num_rows > 0;
    return $subscribed;
}

/**
 * Подписывает одного пользователя на другого
 *
 * @param  mixed $connection
 * @param  mixed $follower_id
 * @param  mixed $author_id
 */
function user_subscribe($connection, $follower_id, $author_id)
{
    $add_subscribe_query = "INSERT INTO subscribe SET follower_id = ?, author_id = ?";
    $remove_subscribe_query = "DELETE FROM subscribe WHERE follower_id = ? AND author_id = ?";
    secure_query($connection, $add_subscribe_query, $follower_id, $author_id);
}

/**
 * Отписывает одного пользователя от другого
 *
 * @param  mixed $connection
 * @param  mixed $follower_id
 * @param  mixed $author_id
 */
function user_unsubscribe($connection, $follower_id, $author_id)
{
    $remove_subscribe_query = "DELETE FROM subscribe WHERE follower_id = ? AND author_id = ?";
    secure_query($connection, $remove_subscribe_query, $follower_id, $author_id);
}

/**
 * Получает пользователя по имени
 *
 * @param  mixed $connection
 * @param  mixed $login
 * @return void
 */
function get_user_by_name($connection, $login)
{
    $select_user_query = "SELECT users.id, users.username, users.avatar FROM users WHERE users.username = ?";
    $user_mysqli = secure_query($connection, $select_user_query, $login);
    $user = mysqli_fetch_assoc($user_mysqli);
    return $user;
}

/**
 * Получает пользователя по id
 *
 * @param  mixed $connection
 * @param  mixed $user_id
 * @return void
 */
function get_user_by_id($connection, $user_id)
{
    $select_user_query = "SELECT users.id, users.username, users.avatar, users.email FROM users WHERE users.id = ?";
    $user_mysqli = secure_query($connection, $select_user_query, $user_id);
    $user = mysqli_fetch_assoc($user_mysqli);
    return $user;
}
