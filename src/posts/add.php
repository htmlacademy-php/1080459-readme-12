<?php

/**
 * Убирает поле, в случае использования другого
 *
 * @param  array $form Массив полей-значений из формы
 * @param  string $field_name Название поля
 * @return array
 */
function ignoreField(array $form, string $field_name): array
{
    unset($form['errors'][$field_name]);
    unset($form['values'][$field_name]);
    return $form;
}

/**
 * Сохраняет пост в БД
 *
 * @param  mysqli $connection
 * @param  array $post
 * @param  array $post_types Типы постов
 * @param  array $user Автор поста
 * @param  string $file_url Путь к файлу
 * @return int id поста в БД
 */
function save_post(mysqli $connection, array $post, array $post_types, array $user, $file_url = null)
{
    $post_id = null;
    $post_type = $post['form-type'];
    $current_time = date('Y-m-d H:i:s');
    $fields = [
        'title',
        'author_id',
        'post_type',
        'content',
        'view_count',
        'dt_add'
    ];

    $parameters = [
        $post['heading'],
        $user['id'],
        $post_types[$post_type],
        $post['content'] ?? null,
        0,
        $current_time
    ];

    if ($post_type == 'quote') {
        array_push($fields, 'quote_author');
        array_push($parameters, $post['quote-author']);
    }

    if ($post_type == 'video') {
        array_push($fields, 'youtube_url');
        array_push($parameters, $post['video-url']);
    }

    if ($post_type == 'photo') {
        array_push($fields, 'img_url');
        array_push($parameters, $file_url);
    }

    if ($post_type == 'link') {
        array_push($fields, 'url');
        array_push($parameters, $post['link-url']);
    }

    $finalFields = [];
    foreach ($fields as $field) {
        $finalFields[] = "{$field} = ?";
    }
    $fields = implode(', ', $finalFields);
    $query = "insert into posts set {$fields}";
    secure_query($connection, $query, ...$parameters);
    $post_id = mysqli_insert_id($connection);

    return $post_id;
}

/**
 * Сохраняет теги в БД
 *
 * @param  mixed $new_tags
 * @param  mixed $post_id
 * @param  mixed $connection
 * @return void
 */
function add_tags(string $new_tags, $post_id, $connection)
{
    $new_tags = htmlspecialchars($new_tags);
    $new_tags = array_unique(explode(' ', $new_tags));
    $select_tags_query = "SELECT * FROM hashtags WHERE tag_name in ('" . implode("','", $new_tags) . "')";
    $tags_mysqli = mysqli_query($connection, $select_tags_query);
    $tags = mysqli_fetch_all($tags_mysqli, MYSQLI_ASSOC);
    foreach ($new_tags as $new_tag) {
        $index = array_search($new_tag, array_column($tags, 'tag_name'));
        if ($index !== false) {
            unset($new_tags[$new_tag]);
            $tag_id = $tags[$index]['id'];
        } else {
            secure_query($connection, "INSERT into hashtags SET tag_name = ?", $new_tag);
            $tag_id = mysqli_insert_id($connection);
        }
        secure_query($connection, "INSERT into post_tags SET post_id = ?, hashtag_id = ?", $post_id, $tag_id);
    }
}

/**
 * Сохраняет изображение из формы либо скачивает изображение по ссылке
 *
 * @param  mixed $form
 * @param  mixed $img_folder
 * @return void
 */
function upload_file(array $form, string $img_folder)
{
    if (isset($form['values']['photo-file'])) {
        return save_image('photo-file', $img_folder);
    }
    $downloadedFileContents = file_get_contents($_POST['photo-url']);
    $file_name = basename($_POST['photo-url']);
    $file_path = $img_folder . $file_name;
    $save = file_put_contents($file_path, $downloadedFileContents);
    return $file_name;
}

/**
 * Возвращает фолловеров заданного пользователя
 *
 * @param  mysqli $connection
 * @param  mixed $author_id
 * @return array
 */
function get_user_followers(mysqli $connection, $author_id): array
{
    $select_followers_query =
    "SELECT users.username, users.email
    FROM users
    INNER JOIN subscribe ON users.id = subscribe.follower_id
    WHERE subscribe.author_id = ?";
    $followers_mysqli = secure_query($connection, $select_followers_query, $author_id);
    $followers = mysqli_fetch_all($followers_mysqli, MYSQLI_ASSOC);
    return $followers;
}
