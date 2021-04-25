<?php

/**
 * Проверяет введенные данные для формы аутентификации
 * @param mysqli $connection
 * @param array $data - отфильтрованный массив $_POST
 * @return array
 */
function validateFormAuth(mysqli $connection, array $data):array
{
    $errors = [];

    if ($error = validateFormAuthLogin($connection, $data['login'])) {
        $errors['login'] = $error;
    }

    if ($error = validateFormAuthPassword($connection, $data['login'], $data['password'])) {
        $errors['password'] = $error;
    }

    return $errors;
}

/**
 * Проверяет логин аутентификации
 * @param mysqli $connection
 * @param string $login - введенный логин
 * @return string|null
 */
function validateFormAuthLogin(mysqli $connection, string $login):?string
{
    if (empty($login)) {
        return 'Необходимо заполнить логин!';
    }

    if (mb_strlen($login) > 50) {
        return 'Превышено количество символов!';
    }

    if ($login) {
        if (!issetLogin($connection, $login)) {
            return 'Пользователя с таким логином не существует';
        }
    }

    return NULL;
}

/**
 * Проверяет пароль аутентификации
 * @param mysqli $connection
 * @param string $login - введенный логин
 * @param string $password - введенный пароль
 * @return string|null
 */
function validateFormAuthPassword(mysqli $connection, string $login, string $password):?string
{
    if (empty($password)) {
        return 'Необходимо заполнить пароль!';
    }

    $issetPassword = issetPassword($connection, $login, $password);

    if (!$issetPassword) {
        return 'Вы ввели не правильный пароль!';
    }

    return NULL;
}