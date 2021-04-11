<?php

require_once 'bootstrap.php';


$formHandledData['errors'] = [];
$formHandledData['data'] = [];

if (!empty($_POST)) {
    $data = filterFormRegistration($_POST, $_FILES);
    $formHandledData = registrationUser($connection, $data);
}


$title = 'Создание поста';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$registrationContent = include_template('registration.php', ['errors' => $formHandledData['errors'], 'data' => $formHandledData['data']]);

$layoutContent = include_template('layout.php', ['mainContent' => $registrationContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
