<?php

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

function get_user_subscribed($connection, $user_id, $author_id)
{
    $select_subscribe_query = "SELECT * FROM subscribe WHERE follower_id = ? AND author_id = ?";
    $user_subscribed_mysqli = secure_query($connection, $select_subscribe_query, $user_id, $author_id);
    $subscribed = $user_subscribed_mysqli->num_rows > 0;
    return $subscribed;
}

function user_subscribe ($connection, $follower_id, $author_id) {
    $select_subscribe_query = "SELECT * FROM subscribe WHERE follower_id = ? AND author_id = ?";
    $add_subscribe_query = "INSERT INTO subscribe SET follower_id = ?, author_id = ?";
    $remove_subscribe_query = "DELETE FROM subscribe WHERE follower_id = ? AND author_id = ?";
    $user_subscribe_mysqli = secure_query($connection, $select_subscribe_query, $follower_id, $author_id);
    if ($user_subscribe_mysqli->num_rows == 0) {
        secure_query($connection, $add_subscribe_query, $follower_id, $author_id);
    } else {
        secure_query($connection, $remove_subscribe_query, $follower_id, $author_id);
    }
}

function get_user_by_name($connection, $login) {
    $select_user_query = "SELECT users.id, users.username, users.avatar FROM users WHERE users.username = ?";
    $user_mysqli = secure_query($connection, $select_user_query, $login);
    $user = mysqli_fetch_assoc($user_mysqli);
    return $user;
}
