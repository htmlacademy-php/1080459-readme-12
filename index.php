<?php
require_once('helpers.php');
require_once('functions.php');
require_once('db.php');

$form_error_codes = [
    'login' => 'Логин',
    'password' => 'Пароль',
];


$validation_rules = [
    'login' => 'filled',
    'password' => 'filled|correctpassword:users,username,password'
];
$con = db_connect("localhost", "root", "", "readme");
if (count($_POST) > 0) {
    foreach ($_POST as $field_name => $field_value) {
        $form['values'][$field_name] = $field_value;
    }
    $form['errors'] = validate($form['values'], $validation_rules, $con);
    $form['errors'] = array_filter($form['errors']);
    if (empty($form['errors'])) {
        session_start();
        $_SESSION['username'] = $form['values']['login'];
    }
}
if (isset($_SESSION) > 0) {
    header("Location: feed.php");
} else {
    $page_content = include_template('anonym.php', ['form_values' => $form['values'], 'form_errors' => $form['errors'], 'form_error_codes' => $form_error_codes]);
    print($page_content);
}
