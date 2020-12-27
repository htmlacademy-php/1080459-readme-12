<?php

require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/posts/like.php');

$validation_rules = [
    'post-id' => 'notexists:posts,id',
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
$post_id = $_GET['id'];
$like_error = validate($connection, ['post-id' => $post_id], $validation_rules);
$like_error = array_filter($like_error);
if (empty($like_error)) {
    like_post($connection, $user['id'], $post_id);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
