<?php

require_once(__DIR__ . '/lib/base.php');
/**
 * @var $connection
 * */
$add_user_query = "INSERT into users SET username = ?, email = ?, password = ?, avatar = ?, dt_add =?";

$validation_rules = [
    'email' => 'filled|correctemail|short:254|exists:users,email',
    'login' => 'filled|short:30|exists:users,username',
    'password' => 'filled|short:50|repeatpassword',
    'password-repeat' => 'filled|repeatpassword'
];
$form_error_codes = [
    'email' => 'Email',
    'login' => 'Логин',
    'password' => 'Пароль',
    'password-repeat' => 'Повторный пароль'
];

$form = [
    'values' => [],
    'errors' => [],
];

$img_folder = __DIR__ . '\\img\\';

if (count($_POST) > 0) {
    $form['values'] = $_POST;
    $form['values']['userpic-file'] = $_FILES['userpic-file'];
    $form['errors'] = validate($connection, $form['values'], $validation_rules);
    $form['errors'] = array_filter($form['errors']);
    if (empty($form['errors'])) {
        $current_time = date('Y-m-d H:i:s');
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $avatar = save_image('userpic-file', $img_folder);
        secure_query(
            $connection,
            $add_user_query,
            $_POST['login'],
            $_POST['email'],
            $password_hash,
            $avatar,
            $current_time
        );
        $post_id = mysqli_insert_id($connection);
        $URL = '/';
        header("Location: $URL");
    }
}
$form_values = $form['values'] ?? [];
$page_content = include_template(
    'reg-template.php',
    [
        'form_values' => $form['values'] ?? [],
        'form_errors' => $form['errors'] ?? [],
        'form_error_codes' => $form_error_codes
    ]
);
print($page_content);
