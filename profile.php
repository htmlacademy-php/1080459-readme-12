<?php

require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/profile.php');

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
$title = $settings['site_name'] . ' | Профиль';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'posts';
$owner_id = isset($_GET['id']) ? (int)$_GET['id'] : $user['id'];
$owner = get_owner($connection, $owner_id);
$user['subscribed'] = get_user_subscribed($connection, $user['id'], $owner_id);
$posts = get_owner_posts($connection, $owner_id);
$likes = get_owner_likes($connection, $owner_id);
$subscribes = get_owner_subscribes($connection, $user['id'], $owner_id);
$add_post_button = true;

$page_content = include_template(
    'profile-template.php',
    [
        'user' => $user,
        'tab' => $tab,
        'owner' => $owner,
        'posts' => $posts,
        'likes' => $likes,
        'subscribes' => $subscribes
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'user' => $user,
        'title' => $title,
        'add_post_button' => $add_post_button
    ]
);
print($layout_content);
