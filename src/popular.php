<?php

/**
 * Получает из БД количество постов,
 *
 * @param  mixed $connection
 * @param  mixed $filter Фильтр по типу контента
 * @return array
 */
function get_total_posts($connection, $filter)
{
    $count_posts_query =
    'SELECT COUNT(*)
    FROM posts
    INNER JOIN content_types ON posts.post_type=content_types.id ';
    if ($filter !== null) {
        $count_posts_query .= "WHERE content_types.type_class = '$filter' ";
    }
    $total_posts_mysqli = mysqli_query($connection, $count_posts_query);
    $total_posts = mysqli_fetch_row($total_posts_mysqli)[0];
    return $total_posts;
}

/**
 * Получает из БД список популярных постов
 *
 * @param  mysqli $connection
 * @param  string $filter Фильтр по типу контента
 * @param  string $sort Сортировка по лайкам/дате/просмотрам
 * @param  int $page_limit Количетство постов на страницу
 * @param  int $page_offset Сколько постов пропускаем
 * @return array
 */
function get_popular_posts($connection, $filter, $sort, $page_limit, $page_offset)
{
    $select_posts_query =
    'SELECT posts.*, users.id AS user_id, users.username, users.avatar, content_types.type_class,
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
    GROUP BY post_id) comment_counts ON comment_counts.post_id = posts.id ';
    if ($filter !== null) {
        $select_posts_query .= "WHERE content_types.type_class = '$filter' ";
    }
    $select_posts_query .= "ORDER BY $sort DESC LIMIT ? OFFSET ?;";
    $posts_mysqli = secure_query($connection, $select_posts_query, $page_limit, $page_offset);
    $posts = mysqli_fetch_all($posts_mysqli, MYSQLI_ASSOC);
    return $posts;
}
