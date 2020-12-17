<?php

/**
 * Сохраняет репост
 *
 * @param  mixed $connection
 * @param  mixed $user_id
 * @param  mixed $post_id
 * @return void
 */
function repost_post($connection, $user_id, $post_id)
{
    $current_time = date('Y-m-d H:i:s');
    $repost = 1;
    $repost_query = "INSERT INTO posts (author_id, dt_add, repost, original_post_id, view_count, original_author_id, post_type, title, content, quote_author, img_url, youtube_url, url)
    SELECT ?, ?, ?, ?, 0, author_id, post_type, title, content, quote_author, img_url, youtube_url, url
    FROM posts
    WHERE id = ?";
    $repost_result = secure_query($connection, $repost_query, $user_id, $current_time, $repost, $post_id, $post_id);
    return $repost_result;
}
