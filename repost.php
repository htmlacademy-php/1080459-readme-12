<?php

require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/posts/view.php');
require_once(__DIR__ . '/src/posts/repost.php');

$validation_rules = [
    'post-id' => 'notexists:posts,id',
];

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    display_404_page();
    exit();
}
$post_id = (int)$_GET['id'];
$repost_error = validate($connection, ['post-id' => $post_id], $validation_rules);
$repost_error = array_filter($repost_error);
$post = get_post($connection, $post_id);
if (empty($repost_error) && ($post['author_id'] !== $user['id']) && (!$post['repost'])) {
    repost_post($connection, $user['id'], $post_id);
}
$URL = '/profile.php?id=' . $user['id'];
header('Location: ' . $URL);
