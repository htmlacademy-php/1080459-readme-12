<?php
require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/feed.php');

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
$title = $settings['site_name'] . ' | Моя лента';
$content_types = get_content_types($connection);
$content_type_names = array_column($content_types, 'type_class');
$filter = white_list($_GET['filter'], $content_type_names);
$posts = get_feed_posts($connection, $filter, $user['id']);
$add_post_button = true;

$page_content = include_template(
    'feed-template.php',
    [
        'posts' => $posts,
        'filter' => $filter,
        'content_types' => $content_types
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'title' => $title,
        'active_section' => 'feed',
        'user' => $user,
        'content' => $page_content,
        'add_post_button' => $add_post_button
    ]
);
print($layout_content);

