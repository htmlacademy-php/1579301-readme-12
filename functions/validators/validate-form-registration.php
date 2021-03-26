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

    return $errors;
}

/**
 * Проверяет email регистрации
 * @param mysqli $connection
 * @param $email - введенный email
 * @return string|null
 */
function validateFormEmail(mysqli $connection, $email)
{
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        return 'Введите корректный email!';
    }

    if ($email) {
        $isEmailIsSet = isEmailIsSet($connection, $email);
        if ($isEmailIsSet) {
            return 'Введенный email уже занят!';
        }
    }

    return NULL;
}

/**
 * Проверяет логин регистрации
 * @param $login - введенный логин
 * @return string|null
 */
function validateFormLogin($login)
{
    if (empty($login)) {
        return 'Необходимо заполнить логин!';
    }

    return NULL;
}

/**
 * Проверяет пароль оегистрации
 * @param $password
 * @return string|null
 */
function validateFormPassword($password)
{
    if (empty($password)) {
        return 'Необходимо заполнить пароль!';
    }

    return NULL;

}

/**
 * Проверяет повтор пароля с ранее введенным
 * @param $password - ранее введенный пароль
 * @param $passwordRepeat - повтор пароля
 * @return string|null

 */
function validateFormPasswordRepeat($password, $passwordRepeat)
{
    if (empty($passwordRepeat)) {
        return 'Необходимо заполнить пароль!';
    }

    if ($passwordRepeat !== $password) {
        return 'Введенный пароль не совпадает';
    }

    return NULL;
}