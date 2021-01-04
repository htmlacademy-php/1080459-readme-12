<?php

require_once(__DIR__ . '/lib/base.php');
require_once(__DIR__ . '/src/posts/view.php');
/** @var $connection */
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
$post = get_post($connection, $post_id);
if ($post === null) {
    display_404_page();
    exit();
}
if (!empty($_SESSION['errors'])) {
    $comment_errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
$author_id = $post['author_id'];
$author = get_post_author($connection, $author_id);
$comments = get_post_comments($connection, $post_id);
$views_mysqli = increase_post_views($connection, $post_id);
$user['subscribed'] = get_user_subscribed($connection, $user['id'], $author_id);

$page_content = include_template(
    'post-details.php',
    [
        'user' => $user,
        'post' => $post,
        'author' => $author,
        'comments' => $comments,
        'comment_errors' => $comment_errors
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'user' => $user,
        'title' => $title
    ]
);
print($layout_content);
