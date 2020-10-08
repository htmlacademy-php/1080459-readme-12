<?php
require_once('helpers.php');
require_once('functions.php');
require_once('db.php');
session_start();

$validation_rules = [
    'text' => [
        'heading' => 'filled',
        'content' => 'filled',
        'tags' => 'filled'
    ],
    'photo' => [
        'heading' => 'filled',
        'photo-url' => 'filled|correctURL|ImageURLContent',
        'tags' => 'filled',
        'photo-file' => 'imgloaded'
    ],
    'link' => [
        'heading' => 'filled',
        'link-url' => 'filled|correctURL',
        'tags' => 'filled'
    ],
    'quote' => [
        'heading' => 'filled',
        'content' => 'filled',
        'quote-author' => 'filled',
        'tags' => 'filled'
    ],
    'video' => [
        'heading' => 'filled',
        'video-url' => 'filled|correctURL|youtubeurl',
        'tags' => 'filled'
    ],
];

$field_error_codes = [
    'heading' => 'Заголовок',
    'content' => 'Контент',
    'tags' => 'Теги',
    'link-url' => 'Ссылка',
    'photo-url' => 'Ссылка из интернета',
    'video-url' => 'Ссылка YOUTUBE',
    'photo-file' => 'Файл фото',
    'quote-author' => 'Автор'
];
$form_type = 'text';
if ($_SESSION['is_auth'] == 1) {
    $user['id'] = $_SESSION['id'];
    $user['name'] = $_SESSION['username'];
    $user['avatar'] = $_SESSION['avatar'];
    $con = db_connect("localhost", "root", "", "readme");
    $content_types_mysqli = mysqli_query($con, $select_content_types_query);
    $content_types = mysqli_fetch_all($content_types_mysqli, MYSQLI_ASSOC);
    $post_types = array_column($content_types, 'id', 'type_class');
    if ((count($_POST) > 0) && isset($_POST['form-type'])){
        $form_type = $_POST['form-type'];
        foreach ($_POST as $field_name => $field_value) {
            $form['values'][$field_name] = $field_value;
        }
        $form['values']['photo-file'] = $_FILES['photo-file'];
        $form['errors'] = validate($form['values'], $validation_rules[$form_type], $con);
        if (empty($form['errors']['photo-file'])) {
            unset($form['errors']['photo-url']);
            unset($form['values']['photo-url']);
        } elseif (empty($form['errors']['photo-url'])) {
            unset($form['errors']['photo-file']);
            unset($form['values']['photo-file']);
        }
        $form['errors'] = array_filter($form['errors']);
        if (empty($form['errors'])) {
            $current_time = date('Y-m-d H:i:s');
            switch ($form_type) {
                case 'quote':
                    secure_query($con, $add_quote_post_query, 'sisiss', $_POST['heading'], $post_types[$form_type], $_POST['content'], $user['id'],$_POST['quote-author'], $current_time);
                    $post_id = mysqli_insert_id($con);
                    break;
                case 'text':
                    secure_query($con, $add_text_post_query, 'sisis', $_POST['heading'], $post_types[$form_type], $_POST['content'], $user['id'], $current_time);
                    $post_id = mysqli_insert_id($con);
                    break;
                case 'link':
                    secure_query($con, $add_link_post_query, 'sisis', $_POST['heading'], $post_types[$form_type], $_POST['link-url'], $user['id'], $current_time);
                    $post_id = mysqli_insert_id($con);
                    break;
                case 'video':
                    secure_query($con, $add_video_post_query, 'sisiss', $_POST['heading'], $post_types[$form_type], $_POST['content'], $user['id'], $_POST['youtube_url'], $current_time);
                    $post_id = mysqli_insert_id($con);
                    break;
                case 'photo':
                    if (isset($form['values']['photo-file'])) {
                        $file_name = $form['values']['photo-file']['name'];
                        $file_path = __DIR__ . '/uploads/';
                        $file_url = '/uploads/' . $file_name;
                        move_uploaded_file($_FILES['photo-file']['tmp_name'], $file_path . $file_name);
                    } else {
                        $file_url = $_POST['photo-url'];
                    }
                    secure_query($con, $add_photo_post_query, 'sisiss', $_POST['heading'], $post_types[$form_type], $_POST['content'], $user['id'], $file_url, $current_time);
                    $post_id = mysqli_insert_id($con);
            }
            $new_tags = array_unique(explode(' ', $_POST['tags']));
            $select_tags_query = "SELECT * FROM hashtags WHERE tag_name in ('".implode("','",$new_tags)."')";
            $tags_mysqli = mysqli_query($con, $select_tags_query);
            $tags = mysqli_fetch_all($tags_mysqli, MYSQLI_ASSOC);
            foreach ($new_tags as $new_tag) {
                $index = array_search($new_tag, array_column($tags, 'tag_name'));
                if ($index !== false) {
                    unset($new_tags[$new_tag]);
                    $tag_id = $tags[$index]['id'];
                } else {
                    secure_query($con, $add_tag_query, 's', $new_tag);
                    $tag_id = mysqli_insert_id($con);
                }
                secure_query($con, $add_post_tag_query, 'ii', $post_id, $tag_id);
            }
            $URL = '/post.php?id='.$post_id;
            header("Location: $URL");
        }
    }
    $page_content = include_template('adding-post.php', ['content_types' => $content_types, 'form_values' => $form['values'], 'form_errors' => $form['errors'], 'field_error_codes' => $field_error_codes, 'form_type' => $form_type, 'user' => $user]);
    print($page_content);
}
header("Location: index.php");
?>
