<?php

/**
 * Получаем пост из БД по id
 *
 * @param  mixed $connection
 * @param  mixed $post_id
 * @return void
 */
function get_post($connection, $post_id)
{
    $select_post_by_id = "SELECT posts.*, users.username, users.avatar, content_types.type_class,
    COALESCE(like_count, 0) AS likes,
    COALESCE(comment_count, 0) AS comments
    FROM posts
    INNER JOIN users ON posts.author_id=users.id
    INNER JOIN content_types ON posts.post_type=content_types.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS like_count
    FROM likes
    GROUP BY post_id) like_counts ON like_counts.post_id = posts.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id) comment_counts ON comment_counts.post_id = posts.id
    WHERE posts.id = ?;";
    $post_mysqli = secure_query($connection, $select_post_by_id, $post_id);
    $post = mysqli_fetch_assoc($post_mysqli);
    return $post;
}

/**
 * Получаем автора поста по id
 *
 * @param  mixed $connection
 * @param  mixed $author_id
 * @return void
 */
function get_post_author($connection, $author_id)
{
    $select_post_author = "SELECT users.id, users.username, users.avatar, users.dt_add,
    COALESCE(followers_count, 0) AS followers,
    COALESCE(posts_count, 0) AS posts
    FROM users
    LEFT JOIN (SELECT author_id, COUNT(*) AS followers_count
    FROM subscribe GROUP BY author_id) followers ON followers.author_id = users.id
    LEFT JOIN (SELECT author_id, COUNT(*) AS posts_count
    FROM posts GROUP BY author_id) author_posts ON author_posts.author_id = users.id
    WHERE users.id = ?";
    $author_mysqli = secure_query($connection, $select_post_author, $author_id);
    $author = mysqli_fetch_assoc($author_mysqli);
    return $author;
}

/**
 * Получаем комментарии к посту по id
 *
 * @param  mixed $connection
 * @param  mixed $post_id
 * @return void
 */
function get_post_comments($connection, $post_id)
{
    $select_post_comments = "SELECT comments.*, users.id AS author_id, users.username AS author_name, users.avatar
    FROM comments INNER JOIN users ON comments.user_id=users.id WHERE post_id = ? ORDER BY dt_add DESC;";
    $comments_mysqli = secure_query($connection, $select_post_comments, $post_id);
    $comments = mysqli_fetch_all($comments_mysqli, MYSQLI_ASSOC);
    return $comments;
}

/**
 * Увеличивает счетчик просмотра поста
 *
 * @param  mixed $connection
 * @param  mixed $post_id
 * @return void
 */
function increase_post_views($connection, $post_id)
{
    $update_post_view_count_query = "UPDATE posts SET view_count = view_count + 1 WHERE id = ?";
    $views_mysqli = secure_query($connection, $update_post_view_count_query, $post_id);
    return $views_mysqli;
}
