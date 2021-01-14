<?php

require_once(__DIR__ . '/lib/base.php');
require_once(__DIR__ . '/src/posts/add.php');
require_once(__DIR__ . '/src/notification.php');

/** @var $connection */

$validation_rules = [
    'text' => [
        'heading' => 'filled',
        'content' => 'filled'
    ],
    'photo' => [
        'heading' => 'filled',
        'photo-url' => 'filled|correctURL|ImageURLContent',
        'photo-file' => 'imgloaded'
    ],
    'link' => [
        'heading' => 'filled',
        'link-url' => 'filled|correctURL'
    ],
    'quote' => [
        'heading' => 'filled',
        'content' => 'filled',
        'quote-author' => 'filled'
    ],
    'video' => [
        'heading' => 'filled',
        'video-url' => 'filled|correctURL|youtubeurl'
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

$title = $settings['site_name'] . ' | Добавить пост';
$img_folder = __DIR__ . '\\img\\';
$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}

$add_post_button = false;
$content_types = get_content_types($connection);
$post_types = array_column($content_types, 'id', 'type_class');

$form = [
    'values' => [],
    'errors' => [],
];


$form_type = $_GET['tab'] ?? 'text';

if (count($_POST) > 0 && isset($_POST['form-type'])) {
    $form_type = $_POST['form-type'];
    $form['values'] = $_POST;
    if (isset($_FILES['photo-file'])) {
        $form['values']['photo-file'] = $_FILES['photo-file'];
    }
    $form['errors'] = validate($connection, $form['values'], $validation_rules[$_POST['form-type']]);

    if (empty($form['errors']['photo-file'])) {
        $form = ignoreField($form, 'photo-url');
    } elseif (empty($form['errors']['photo-url'])) {
        $form = ignoreField($form, 'photo-file');
    }

    $form['errors'] = array_filter($form['errors']);
    if (empty($form['errors'])) {
        $file_url = ($form_type === 'photo') ? upload_file($form, $img_folder) : null;
        $post_id = save_post($connection, $form['values'], $post_types, $user, $file_url);
        (empty($_POST['tags'])) ?: add_tags($_POST['tags'], $post_id, $connection);
        $followers = get_user_followers($connection, $user['id']);
        new_post_notification(
            $followers,
            $settings['smtp'],
            $user,
            $form['values']['heading'],
            $post_id,
            $settings['site_url']
        );
        $URL = '/post.php?id=' . $post_id;
        header("Location: $URL");
    }
}

$page_content = include_template(
    'add-template.php',
    [
        'content_types' => $content_types,
        'form_values' => $form['values'] ?? [],
        'form_errors' => $form['errors'] ?? [],
        'field_error_codes' => $field_error_codes,
        'form_type' => $form_type,
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
