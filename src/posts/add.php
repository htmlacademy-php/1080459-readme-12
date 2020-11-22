<?php

function ignoreField (array $form, string $field_name)
{
    unset($form['errors'][$field_name]);
    unset($form['values'][$field_name]);
    return $form;
}

function save_post($connection, array $post, array $post_types, array $user, $file_url = null)
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
        $post['content'],
        0,
        $current_time
    ];

    if ($post_type === 'quote') {
        array_push($fields, 'quote_author');
        array_push($parameters, $post['quote-author']);
    }

    if ($post_type === 'video') {
        array_push($fields, 'youtube_url');
        array_push($parameters, $post['youtube_url']);
    }

    if ($post_type === 'photo') {
        array_push($fields, 'img_url');
        array_push($parameters, $file_url);
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

function add_tags(string $new_tags, $post_id, $connection)
{
    $new_tags = array_unique(explode(' ', $new_tags));
    // Небезопасный запрос.
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

function upload_file(array $form, string $img_folder)
{
    if (isset($form['values']['photo-file'])) {
        $file_name = $form['values']['photo-file']['name'];
        $file_path = $img_folder . $file_name;
        move_uploaded_file($_FILES['photo-file']['tmp_name'], $file_path);
    } else {
        $downloadedFileContents = file_get_contents($_POST['photo-url']);
        $file_name = basename($_POST['photo-url']);
        $file_path = $img_folder . $file_name;
        $save = file_put_contents($file_path, $downloadedFileContents);
    }

    return $file_name;
}

function get_user_followers($connection, $author_id)
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
