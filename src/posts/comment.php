<?php

/**
 * Сохраняет комментарий в БД
 *
 * @param  mixed $connection
 * @param  mixed $user_id
 * @param  mixed $post_id
 * @param  mixed $comment
 * @return void
 */
function post_comment($connection, $user_id, $post_id, $comment)
{
    $add_comment_query = "INSERT into comments SET user_id = ?, post_id = ?, dt_add = ?, content = ?";
    $comment = trim($comment);
    $current_time = date('Y-m-d H:i:s');
    $comment_mysqli = secure_query($connection, $add_comment_query, $user_id, $post_id, $current_time, $comment);
    return $comment_mysqli;
}
