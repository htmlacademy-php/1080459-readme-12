<?php

/**
 * Получает список диалогов - пользователей от которых или которым есть сообщения
 *
 * @param  mixed $connection
 * @param  mixed $user_id Пользователь
 * @return array Список диалогов
 */
function get_dialogs($connection, $user_id)
{
    $select_dialogs_query = "SELECT dialog, username, avatar, content, sender_id, last_message
    FROM messages
    INNER JOIN (SELECT MAX(dt_add) AS last_message,
    IF (receiver_id = ?, sender_id, receiver_id) AS dialog
    FROM messages
    WHERE sender_id = ? OR receiver_id = ?
    GROUP BY dialog) groups
    ON messages.dt_add = groups.last_message
    INNER JOIN users
    ON users.id = dialog
    ORDER BY last_message DESC ";
    $dialogs_mysqli = secure_query($connection, $select_dialogs_query, $user_id, $user_id, $user_id);
    while ($dialogs = mysqli_fetch_array($dialogs_mysqli, MYSQLI_ASSOC)) {
        $dialogsAssoc[$dialogs['dialog']] = array_slice($dialogs, 1);
        $dialogsAssoc[$dialogs['dialog']]['messages'] = [];
    }
    return $dialogsAssoc;
}

/**
 * Получает список отправленных и полученных сообщений пользователя
 *
 * @param  mixed $connection
 * @param  mixed $user_id Пользователь
 * @return array Список сообщений
 */
function get_messages($connection, $user_id)
{
    $select_messages_query =
    "SELECT content, dt_add, sender_id, receiver_id,
    IF (receiver_id = ?, sender_id, receiver_id) AS dialog
    FROM messages
    WHERE sender_id = ? OR receiver_id = ?
    ORDER BY dt_add ASC";
    $messages_mysqli = secure_query($connection, $select_messages_query, $user_id, $user_id, $user_id);
    $messages = mysqli_fetch_all($messages_mysqli, MYSQLI_ASSOC);
    return $messages;
}

/**
 * Добавляет сообщение в БД
 *
 * @param  mysqli $connection
 * @param  mixed $sender_id Отправитель
 * @param  mixed $receiver_id Получатель
 * @param  mixed $message Сообщение
 * @return mixed Результат выполнения запроса
 */
function add_message($connection, $sender_id, $receiver_id, string $message)
{
    $add_message_query =
    "INSERT into messages
    SET sender_id = ?, receiver_id = ?, dt_add = ?, content = ?";
    $message = trim($message);
    $current_time = date('Y-m-d H:i:s');
    $message_mysqli = secure_query($connection, $add_message_query, $sender_id, $receiver_id, $current_time, $message);
    return $message_mysqli;
}
