<?php
require_once(__DIR__ . '/lib/base.php');
/** @var $connection */
require_once(__DIR__ . '/src/messages.php');

$validation_rules = [
    'receiver-id' => 'notexists:users,id',
    'message' => 'filled|long:4'
];

$user = get_user();
if ($user === null) {
    header("Location: index.php");
    exit();
}
$title = $settings['site_name'] . ' | Сообщения';


if (count($_POST) > 0 && isset($_POST['receiver-id']) && ($_POST['receiver-id'] != $user['id'])) {
    $receiver_id = $_POST['receiver-id'];
    $form['values'] = $_POST;
    $form['errors'] = validate($connection, $form['values'], $validation_rules);
    $form['errors'] = array_filter($form['errors']);
    if (empty($form['errors'])) {
        $message = add_message($connection, $user['id'], $receiver_id, $_POST['message']);
        header("Location: ".$_SERVER['PHP_SELF']);
    } else {
        $errors = $form['errors'];
    }
}

$dialogs = get_dialogs($connection, $user['id']);
$active_dialog_id = $_GET['id'] ?? array_key_first($dialogs);
$messages = get_messages($connection, $user['id']);
foreach ($messages as $message) {
    array_push($dialogs[$message['dialog']]['messages'], $message);
}

$page_content = include_template(
    'messages-template.php',
    [
        'user' => $user,
        'dialogs' => $dialogs,
        'messages' => $messages,
        'message_errors' => $errors,
        'active_dialog_id' => $active_dialog_id
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'title' => $title,
        'user' => $user,
        'content' => $page_content,
        'active_section' => 'popular'
    ]
);
print($layout_content);
