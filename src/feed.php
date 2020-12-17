<?php

/**
 * Получает список постов для ленты пользователя
 *
 * @param  mysqli $connection
 * @param  string $filter Фильтр по типу контента
 * @param  mixed $follower_id id пользователя
 * @return array Список постов
 */
function get_feed_posts($connection, $filter, $user_id)
{
    $select_post_tags_query =
    'SELECT post_tags.*, tag_name
    FROM post_tags
    INNER JOIN hashtags ON post_tags.hashtag_id = hashtags.id';

    $select_posts_query = "SELECT posts.*, content_types.type_class, users.id AS user_id, users.username, users.avatar,
    COALESCE(like_count, 0) AS likes,
    COALESCE(comment_count, 0) AS comments,
    COALESCE(repost_count, 0) AS reposts,
    null AS tags
    FROM posts
    INNER JOIN users ON posts.author_id=users.id
    INNER JOIN content_types ON posts.post_type=content_types.id
    INNER JOIN subscribe ON posts.author_id = subscribe.author_id
    LEFT JOIN (SELECT post_id, COUNT(*) AS like_count
    FROM likes
    GROUP BY post_id) like_counts ON like_counts.post_id = posts.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id) comment_counts ON comment_counts.post_id = posts.id
    LEFT JOIN (SELECT original_post_id, COUNT(*) AS repost_count FROM posts
    GROUP BY original_post_id) repost_counts ON repost_counts.original_post_id = posts.id
    WHERE subscribe.follower_id = $user_id ";

    if ($filter) {
        $select_posts_query.= "AND content_types.type_class = '$filter' ";
    }
    $select_posts_query.= 'ORDER BY dt_add DESC';

    $tags_mysqli = mysqli_query($connection, $select_post_tags_query);
    $tags = mysqli_fetch_all($tags_mysqli, MYSQLI_ASSOC);
    $posts_mysqli = mysqli_query($connection, $select_posts_query);
    $posts = mysqli_fetch_all($posts_mysqli, MYSQLI_ASSOC);
    $posts_ids = array_column($posts, 'id');
    foreach ($tags as $tag) {
        $key = array_search($tag['post_id'], $posts_ids);
        if ($key !== FALSE) {
            $posts[$key]['tags'][$tag['hashtag_id']] = $tag['tag_name'];
        }
    }
    return $posts;
}
