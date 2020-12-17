<?php

/**
 * Получает из БД данные пользователя - владельца профиля
 *
 * @param  mixed $connection
 * @param  mixed $owner_id
 * @return array
 */
function get_owner($connection, $owner_id): array
{
    $select_owner_query =
    "SELECT users.id, users.username, users.avatar, users.dt_add, COUNT(subscribe.author_id) AS followers
    FROM users
    LEFT JOIN subscribe ON users.id = subscribe.author_id
    WHERE users.id = ?
    GROUP BY users.id";
    $owner_mysqli = secure_query($connection, $select_owner_query, $owner_id);
    $owner = mysqli_fetch_assoc($owner_mysqli);
    return $owner;
}

/**
 * Получает список постов пользователя - владельца профиля
 *
 * @param  mysqli $connection
 * @param  mixed $owner_id
 * @return array
 */
function get_owner_posts($connection, $owner_id)
{
    $select_post_tags_query =
    'SELECT post_tags.*, tag_name
    FROM post_tags
    INNER JOIN hashtags ON post_tags.hashtag_id = hashtags.id';
    $select_user_posts_query =
    "SELECT posts.*, content_types.type_class, users.id AS user_id, users.username, users.avatar, original_author_avatar, original_author_username,
    COALESCE(like_count, 0) AS likes,
    COALESCE(comment_count, 0) AS comments,
    COALESCE(repost_count, 0) AS reposts,
    null AS tags
    FROM posts
    INNER JOIN users ON posts.author_id=users.id
    INNER JOIN content_types ON posts.post_type=content_types.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS like_count
    FROM likes
    GROUP BY post_id) like_counts ON like_counts.post_id = posts.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id) comment_counts ON comment_counts.post_id = posts.id
    LEFT JOIN (SELECT original_post_id, COUNT(*) AS repost_count FROM posts
    GROUP BY original_post_id) repost_counts ON repost_counts.original_post_id = posts.original_post_id
	LEFT JOIN (SELECT users.id, users.avatar AS original_author_avatar, users.username AS original_author_username FROM users) original_author ON posts.original_author_id = original_author.id
    WHERE users.id = ?
    ORDER BY dt_add DESC";
    $tags_mysqli = mysqli_query($connection, $select_post_tags_query);
    $tags = mysqli_fetch_all($tags_mysqli, MYSQLI_ASSOC);
    $posts_mysqli = secure_query($connection, $select_user_posts_query, $owner_id);
    $posts = mysqli_fetch_all($posts_mysqli, MYSQLI_ASSOC);
    $posts_ids = array_column($posts, 'id');
    foreach ($tags as $tag) {
        $index = array_search($tag['post_id'], $posts_ids);
        if ($index !== FALSE) {
            $posts[$index]['tags'][$tag['hashtag_id']] = $tag['tag_name'];
        }
    }
    return $posts;
}

/**
 * Получает список лайков, поставленных пользователю
 *
 * @param  mysqli $connection
 * @param  mixed $owner_id
 * @return array
 */
function get_owner_likes($connection, $owner_id)
{
    $select_owner_likes = "SELECT likes.user_id, likes.post_id, posts.title, posts.content, users.id AS user_id, users.username, users.avatar,content_types.type_class
    FROM likes
    INNER JOIN posts ON posts.id = likes.post_id AND posts.author_id = ?
    INNER JOIN content_types ON posts.post_type=content_types.id
    INNER JOIN users ON users.id = likes.user_id";
    $likes_mysqli = secure_query($connection, $select_owner_likes, $owner_id);
    $likes = mysqli_fetch_all($likes_mysqli, MYSQLI_ASSOC);
    return $likes;
}

/**
 * Получает список подписчиков пользователя
 *
 * @param  mysqli $connection
 * @param  mixed $user_id
 * @param  mixed $owner_id
 * @return array
 */
function get_owner_subscribes($connection, $user_id, $owner_id)
{
    $select_owner_subscribes = "SELECT users.id AS user_id, users.avatar, users.username, users.dt_add,
    COALESCE(post_count, 0) AS post_count,
    COALESCE(user_subscribe, 0) AS user_subscribe
    FROM subscribe
    INNER JOIN users ON subscribe.author_id = users.id
    LEFT JOIN (SELECT author_id, COUNT(*) AS post_count
    FROM posts
    GROUP BY author_id) post_counts ON post_counts.author_id = users.id
    LEFT JOIN (SELECT author_id, follower_id AS user_subscribe FROM subscribe WHERE follower_id = ?) user_subscribed ON user_subscribed.author_id = users.id
    WHERE subscribe.follower_id = ?";
    $subscribes_mysqli = secure_query($connection, $select_owner_subscribes, $user_id, $owner_id);
    $subscribes = mysqli_fetch_all($subscribes_mysqli, MYSQLI_ASSOC);
    return $subscribes;
}


