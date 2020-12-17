<?php

/**
 * Сохраняет лайк в БД
 *
 * @param  mixed $connection
 * @param  mixed $user_id
 * @param  mixed $post_id
 * @return void
 */
function like_post($connection, $user_id, $post_id)
{
    $like_query = "INSERT INTO likes SET user_id = ?, post_id = ?";
    $like_result = secure_query($connection, $like_query, $user_id, $post_id);
    return $like_result;
}
