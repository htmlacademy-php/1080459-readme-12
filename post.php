<?php
require_once('helpers.php');
require_once('functions.php');
session_start();
$select_post_by_id = "SELECT posts.*, users.username, users.avatar, content_types.type_class FROM posts INNER JOIN users ON posts.author_id=users.id INNER JOIN content_types ON posts.post_type=content_types.id WHERE posts.id = ? ORDER BY view_count DESC;";
$count_posts_by_author = "SELECT COUNT(*) FROM posts WHERE author_id = ?;";
$count_author_followers = "SELECT COUNT(*) FROM subscribe WHERE author_id = ?;";
if (!isset($_GET['id'])) {
    display_404_page();
    exit();
}
if ($_SESSION['is_auth'] == 1) {
    $user['name'] = $_SESSION['username'];
    $user['id'] = $_SESSION['id'];
    $user['avatar'] = $_SESSION['avatar'];
    $con = mysqli_connect("localhost", "root", "", "readme");
    if ($con == false) {
        $error = mysqli_connect_error();
        print($error);
        http_response_code(500);
        exit();
    }
    mysqli_set_charset($con, "utf8");
    $post_id = $_GET['id'];
    $posts_mysqli = secure_query($con, $select_post_by_id, 'i', $post_id);
    if (!mysqli_num_rows($posts_mysqli)) {
        $page_not_found = true;
        display_404_page();
        exit();
    }
    $posts_array = mysqli_fetch_all($posts_mysqli, MYSQLI_ASSOC);
    $post_author_id = $posts_array[0]['author_id'];
    $author_posts_count_mysqli = secure_query($con, $count_posts_by_author, 'i', $post_author_id);
    $author_posts_count = mysqli_fetch_row($author_posts_count_mysqli)[0];
    $author_followers_count_mysqli = secure_query($con, $count_author_followers, 'i', $post_author_id);
    $author_followers_count = mysqli_fetch_row($author_followers_count_mysqli)[0];
    $page_content = include_template('post-details.php', ['post' => $posts_array[0], 'author_posts_count' => $author_posts_count, 'author_followers_count' => $author_followers_count]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'user' => $user, 'title' => $title]);
    print($layout_content);
    mysqli_close($con);
    exit();
}
header("Location: index.php");


