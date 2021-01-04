<?php

/**
 * Валидирует и сохраняет комментарии к посту
 *  */

require_once __DIR__ . '/lib/base.php';
require_once __DIR__ . '/src/posts/comment.php';
/**
 *  @var $connection
 * */

$validation_rules = [
    'post-id' => 'notexists:posts,id',
    'comment' => 'filled|long:4'
];
$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
if (count($_POST) == 0 || !isset($_POST['post-id'])) {
    header("Location: index.php");
    exit();
}
$post_id = $_POST['post-id'];
$form['values'] = $_POST;
$form['errors'] = validate($connection, $form['values'], $validation_rules);
$form['errors'] = array_filter($form['errors']);
if (empty($form['errors'])) {
    $comment = post_comment($connection, $user['id'], $post_id, $_POST['comment']);
} else {
    $_SESSION['errors'] = $form['errors'];
}
$URL = '/post.php?id=' . $post_id;
header("Location: $URL");
