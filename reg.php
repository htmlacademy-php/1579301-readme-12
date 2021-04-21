<?php

require_once 'bootstrap.php';


$formHandledData['errors'] = [];
$formHandledData['data'] = [];

if (!empty($_POST)) {
    $data = filterFormRegistration($_POST, $_FILES);
    $formHandledData = registrationUser($connection, $data);
}

$title = 'Создание поста';

var_export($_SESSION);
$user_name = 'Dima'; // укажите здесь ваше имя

$registrationContent = include_template('registration.php', ['errors' => $formHandledData['errors'], 'data' => $formHandledData['data']]);

$layoutContent = include_template('layout.php', ['mainContent' => $registrationContent, 'title' => $title, 'is_auth' => isAuth($_SESSION), 'user_name' => $user_name]);

print $layoutContent;
