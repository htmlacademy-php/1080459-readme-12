<?php

require_once(__DIR__ . '/lib/base.php');
require_once(__DIR__ . '/src/notification.php');
/** @var $connection */

$validation_rules = [
    'author_id' => 'notexists:users,id',
];

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
if (!isset($_GET['id'])) {
    display_404_page();
    exit();
}
$author_id = $_GET['id'];
if ($author_id == $user['id']) {
    header("Location: profile.php?id=" . $author_id);
    exit();
}
$subscribe_error = validate($connection, ['author_id' => $author_id], $validation_rules);
$subscribe_error = array_filter($subscribe_error);
if (empty($subscribe_error)) {
    $author = get_user_by_id($connection, $author_id);
    if (!get_user_subscribed($connection, $user['id'], $author_id)) {
        user_subscribe($connection, $user['id'], $author_id);
        new_follower_notification($author, $user, $settings['smtp'], $settings['site_url']);
    } else {
        user_unsubscribe($connection, $user['id'], $author_id);
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
