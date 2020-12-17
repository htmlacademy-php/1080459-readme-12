<?php

/**
 * Отправляет уведомление о новом сообщении
 *
 * @param  mixed $mailing_list
 * @param  mixed $smtp_settings
 * @param  mixed $post_author
 * @param  mixed $post_title
 * @param  mixed $post_id
 * @param  mixed $site_url
 * @return void
 */
function new_post_notification($mailing_list, $smtp_settings, $post_author, $post_title, $post_id, $site_url)
{
    $transport = new Swift_SmtpTransport($smtp_settings['server'], $smtp_settings['port']);
    $transport->setUsername($smtp_settings['user']);
    $transport->setPassword($smtp_settings['password']);
    $subject = "Новая публикация от пользователя " . $post_author['name'];
    $message = new Swift_Message($subject);
    $message->setFrom($smtp_settings['sender'], "ReadMe");
    foreach ($mailing_list as $recepient) {
        $body = "Здравствуйте, " . $recepient['username'] . ". Пользователь " . $post_author['name'] .
        " только что опубликовал новую запись " . "«" . $post_title . "». " .
        "Посмотрите её на странице пользователя: " . $site_url . "/profile.php?id=" . $post_author['id'];
        $message->setTo([$recepient['email']]);
        $message->setBody($body);
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    }
}

/**
 * Отправляет уведомление о новом подписчике
 *
 * @param  mixed $recepient
 * @param  mixed $follower
 * @param  mixed $smtp_settings
 * @param  mixed $site_url
 * @return void
 */
function new_follower_notification($recepient, $follower, $smtp_settings, $site_url)
{
    $transport = new Swift_SmtpTransport($smtp_settings['server'], $smtp_settings['port']);
    $transport->setUsername($smtp_settings['user']);
    $transport->setPassword($smtp_settings['password']);
    $subject = 'У вас новый подписчик';
    $message = new Swift_Message($subject);
    $message->setFrom($smtp_settings['sender'], "ReadMe");
    $body = "Здравствуйте, " . $recepient['username'] . ". На вас подписался новый пользователь " . $follower['name'] .
    " Вот ссылка на его профиль: " . $site_url . "/profile.php?id=" . $follower['id'];
    $message->setTo([$recepient['email']]);
    $message->setBody($body);
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
