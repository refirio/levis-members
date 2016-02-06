<?php

/*********************************************************************

 Functions for User

*********************************************************************/

import('libs/plugins/cookie.php');

function service_user_autologin()
{
    if (empty($_SESSION['session']) && empty($_SESSION['user']) && !empty($_COOKIE['session'])) {
        //セッション情報を取得
        $users = select_sessions(array(
            'select' => 'user_id, keep',
            'where'  => array(
                'id = :id AND expire > :expire',
                array(
                    'id'     => $_COOKIE['session'],
                    'expire' => localdate('Y-m-d H:i:s'),
                ),
            ),
        ));
        if (!empty($users)) {
            if ($users[0]['keep']) {
                $_SESSION['user'] = $users[0]['user_id'];
            }

            //セッション情報を更新
            $session = rand_string();

            $resource = update_sessions(array(
                'set'   => array(
                    'id'     => $session,
                    'agent'  => $_SERVER['HTTP_USER_AGENT'],
                    'expire' => localdate('Y-m-d H:i:s', time() + $GLOBALS['cookie_expire']),
                ),
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_COOKIE['session'],
                    ),
                ),
            ));
            if ($resource) {
                cookie_set('session', $session, time() + $GLOBALS['cookie_expire']);
            } else {
                error('データを編集できません。');
            }

            if ($users[0]['keep']) {
                //ユーザ情報を更新
                $resource = update_users(array(
                    'set'   => array(
                        'loggedin'    => localdate('Y-m-d H:i:s'),
                    ),
                    'where' => array(
                        'id = :id',
                        array(
                            'id' => $_SESSION['user'],
                        ),
                    ),
                ));
                if (!$resource) {
                    error('データを編集できません。');
                }
            }
        }

        $_SESSION['session'] = true;
    } elseif (!empty($_SESSION['user'])) {
        //会員情報を取得
        $users = select_users(array(
            'select' => 'id',
            'where'  => array(
                'id = :id',
                array(
                    'id' => $_SESSION['user'],
                ),
            ),
        ));
        if (empty($users)) {
            unset($_SESSION['user']);
        }
    }

    return;
}
