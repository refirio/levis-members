<?php

import('libs/plugins/mail.php');

/**
 * メールの送信
 *
 * @param  string  $to
 * @param  string  $subject
 * @param  string  $message
 * @param  array  $headers
 * @param  array  $files
 * @return bool
 */
function service_mail_send($to, $subject, $message, $headers = array(), $files = array())
{
    if ($GLOBALS['mail_send'] === true) {
        return mail_send($to, $subject, $message, $headers);
    } else {
        $text  = '――――――――――――――――――――' . "\n";
        $text .= 'to: ' . $to . "\n";
        $text .= '――――――――――――――――――――' . "\n";
        $text .= 'subject: ' . $subject . "\n";
        $text .= '――――――――――――――――――――' . "\n";
        $text .= $message;

        return file_put_contents(MAIN_APPLICATION_PATH . 'mails/' . localdate('YmdHis') . '_' . $to . '.txt', $text);
    }
}
