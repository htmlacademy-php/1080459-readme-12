<?php
require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/popular.php');

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
$title = $settings['site_name'] . ' | Популярное';
$page_limit = $settings['page_limit'];
$page_number = $_GET['page'] ?? '1';
$page_limit = $_GET['limit'] ?? $page_limit;
$page_offset = ($page_number - 1) * $page_limit;
$content_types = get_content_types($connection);
$content_type_names = array_column($content_types, 'type_class');
$filter = white_list($_GET['filter'], $content_type_names);
$sort = $_GET['sort'] ?? 'view_count';
$sort = white_list($sort, ["likes","view_count","dt_add"]);

$total_posts = get_total_posts($connection, $filter);
$posts = get_popular_posts($connection, $filter, $sort, $page_limit, $page_offset);
$add_post_button = true;

$page_content = include_template(
    'popular-template.php',
    [
        'posts' => $posts,
        'total_posts' => $total_posts,
        'filter' => $filter,
        'sort' => $sort,
        'page_number' => $page_number,
        'page_limit' => $page_limit,
        'content_types' => $content_types
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'title' => $title,
        'user' => $user,
        'content' => $page_content,
        'active_section' => 'popular',
        'add_post_button' => $add_post_button
    ]
);
print($layout_content);
