<?php

require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/posts/search.php');

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}

if (count($_GET) > 0) {
    $keywords = trim($_GET['keywords']);
    if ($keywords == '') {
        $page_content = include_template('no-results.php');
        print($page_content);
        exit();
    }
    $search_results = search_posts($connection, $keywords);
    if (count($search_results) == 0) {
        $page_content = include_template('no-results.php', ['keywords' => $keywords]);
        print($page_content);
        exit();
    }
}
$add_post_button = true;
$page_content = include_template(
    'search-template.php',
    [
        'keywords' => $keywords,
        'posts' => $search_results
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'title' => $title,
        'user' => $user,
        'content' => $page_content,
        'add_post_button' => $add_post_button
    ]
);
print($layout_content);
