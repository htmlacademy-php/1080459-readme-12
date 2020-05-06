<?php
require_once('helpers.php');
require_once('functions.php');
$select_post_by_id = "SELECT posts.*, users.username, users.avatar, content_types.type_class FROM posts INNER JOIN users ON posts.author_id=users.id INNER JOIN content_types ON posts.post_type=content_types.id WHERE posts.id = ? ORDER BY view_count DESC;";
$con = mysqli_connect("localhost", "root", "", "readme");
$page_not_found = false;
if ($con == false) {
    $error = mysqli_connect_error();
    print($error);
} else {
    mysqli_set_charset($con, "utf8");
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $select_post_by_id_query_stmt = mysqli_prepare($con, $select_post_by_id);
        mysqli_stmt_bind_param($select_post_by_id_query_stmt, 'i', $id);
        mysqli_stmt_execute($select_post_by_id_query_stmt);
        $posts_mysqli = mysqli_stmt_get_result($select_post_by_id_query_stmt);
        if (!mysqli_num_rows($posts_mysqli)) {
            $page_not_found = true;
        } else {
            $posts_array = mysqli_fetch_all($posts_mysqli, MYSQLI_ASSOC);
            $post_author_id = $posts_array[0]['author_id'];
            $count_posts_by_author = "SELECT COUNT(*) FROM posts WHERE author_id = ".$post_author_id.";";
            $author_posts_count_mysqli = mysqli_query($con, $count_posts_by_author);
            $author_posts_count_array = mysqli_fetch_all($author_posts_count_mysqli, MYSQLI_ASSOC);
            $author_posts_count = $author_posts_count_array[0]['COUNT(*)'];
            $count_author_followers = "SELECT COUNT(*) FROM subscribe WHERE author_id = ".$post_author_id.";";
            $author_followers_count_mysqli = mysqli_query($con, $count_author_followers);
            $author_followers_count_array = mysqli_fetch_all($author_followers_count_mysqli, MYSQLI_ASSOC);
            $author_followers_count = $author_followers_count_array[0]['COUNT(*)'];
            $page_content = include_template('post-details.php', ['post' => $posts_array[0],'author_posts_count' => $author_posts_count, 'author_followers_count' => $author_followers_count]);
            print($page_content);
        }
    } else {
        $page_not_found = true;
    }
    if ($page_not_found) {
        $page_content = include_template('404.php');
        $layout_content = include_template('layout.php',['content' => $page_content]);
        print($layout_content);
    }
    mysqli_close($con);
}


