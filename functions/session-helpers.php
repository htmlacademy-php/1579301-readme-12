<?php

/**
 * Проверяет аутентифицирован пользователь или нет
 * @param array $session - входящий глобальный массив $_SESSION
 * @return bool
 */
function authBySession(array $session)
{
    if (isset($session['user_id'])) {
        return true;
    }

    return false;
}


