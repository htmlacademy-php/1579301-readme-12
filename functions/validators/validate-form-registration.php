<?php

function validateFormRegistration(mysqli $connection, $data)
{
    $errors = [];

    if ($error = validateFormEmail($connection, $data['email'])) {
        $errors['email'] = $error;
    }

    if ($error = validateFormLogin($data['login'])) {
        $errors['login'] = $error;
    }

    if ($error = validateFormPassword($data['password'])) {
        $errors['password'] = $error;
    }

    if ($error = validateFormPasswordRepeat($data['password'], $data['password-repeat'])) {
        $errors['password-repeat'] = $error;
    }

    if ($error = validateFormAvatar($data['avatar']['type'] ?? NULL)) {
        $errors['avatar'] = $error;
    }

    return $errors;
}

/**
 * Проверяет email регистрации
 * @param mysqli $connection
 * @param string $email - введенный email
 * @return string|null
 */
function validateFormEmail(mysqli $connection, string $email):?string
{
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        return 'Введите корректный email!';
    }

    if (mb_strlen($email) > 100) {
        return 'Превышена длина в 100 символов!';
    }

    if ($email) {
        $issetEmail = issetEmail($connection, $email);
        if ($issetEmail) {
            return 'Введенный email уже занят!';
        }
    }

    return NULL;
}

/**
 * Проверяет логин регистрации
 * @param string $login - введенный логин
 * @return string|null
 */
function validateFormLogin(string $login):?string
{
    if (empty($login)) {
        return 'Необходимо заполнить логин!';
    }

    if (mb_strlen($login) > 50) {
        return 'Превышено количество символов!';
    }

    return NULL;
}

/**
 * Проверяет пароль регистрации
 * @param string $password
 * @return string|null
 */
function validateFormPassword(string $password):?string
{
    if (empty($password)) {
        return 'Необходимо заполнить пароль!';
    }

    return NULL;
}

/**
 * Проверяет повтор пароля с ранее введенным
 * @param string $password - ранее введенный пароль
 * @param string $passwordRepeat - повтор пароля
 * @return string|null

 */
function validateFormPasswordRepeat($password, $passwordRepeat):?string
{
    if (empty($passwordRepeat)) {
        return 'Необходимо заполнить пароль!';
    }

    if ($passwordRepeat !== $password) {
        return 'Введенный пароль не совпадает';
    }

    return NULL;
}

/**
 * Проверяет mime тип файла на соответствие разрешенным
 * @param $avatarMimeType - mime тип загружаемого файла
 * @return string|null
 */
function validateFormAvatar($avatarMimeType)
{
    $allowedMimeType = ['image/png', 'image/jpeg', 'image/gif', 'image/x-png'];

    if (isset($avatarMimeType)) {
        if (!in_array($avatarMimeType, $allowedMimeType)) {
            return 'Разрешенные разрешения image/png, image/jpeg, image/gif, image/x-png';
        }
    }

    return NULL;
}
