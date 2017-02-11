<?php

import('libs/plugins/mail.php');

/**
 * メールの送信
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param array  $headers
 * @param array  $files
 *
 * @return bool
 */
function service_mail_send($to, $subject, $message, $headers = array(), $files = array())
{
    $result = false;

    if ($GLOBALS['config']['mail_send'] === true) {
        $result = mail_send($to, $subject, $message, $headers);
        if (!$result) {
            return $result;
        }
    }
    if ($GLOBALS['config']['mail_log'] === true) {
        $directory = MAIN_APPLICATION_PATH . 'mail/' . localdate('Ymd') . '/';

        if (!is_dir($directory)) {
            if (mkdir($directory, 0707)) {
                chmod($directory, 0707);
            }
        }

        $text  = '――――――――――――――――――――' . "\n";
        $text .= 'to: ' . $to . "\n";
        $text .= '――――――――――――――――――――' . "\n";
        $text .= 'subject: ' . $subject . "\n";
        $text .= '――――――――――――――――――――' . "\n";
        $text .= $message;

        $result = file_put_contents($directory . localdate('His') . '_' . $to . '.txt', $text);
        if (!$result) {
            return $result;
        }
    }

    return $result;
}
