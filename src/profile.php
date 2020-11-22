<?php
function get_owner($connection, $owner_id)
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

function get_owner_posts($connection, $owner_id)
{
    $select_user_posts_query =
    "SELECT posts.*, users.id AS user_id, users.username, users.avatar, content_types.type_class,
    COUNT(likes.post_id) AS likes, COUNT(comments.post_id) AS comments
    FROM posts
    INNER JOIN users ON posts.author_id=users.id
    INNER JOIN content_types ON posts.post_type=content_types.id
    LEFT OUTER JOIN comments ON posts.id = comments.post_id
    LEFT OUTER JOIN likes ON posts.id = likes.post_id
    WHERE posts.author_id = ?
    GROUP BY posts.id
    ORDER BY dt_add DESC;";
    $posts_mysqli = secure_query($connection, $select_user_posts_query, $owner_id);
    $posts = mysqli_fetch_all($posts_mysqli, MYSQLI_ASSOC);
    return $posts;
}

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


