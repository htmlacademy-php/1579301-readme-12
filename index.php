<?php

require_once 'bootstrap.php';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'Регистрация и аутентификация';

$formHandledData['errors'] = [];
$formHandledData['data'] = [];

if (!empty($_POST)) {
    $data = filterFormAuth($_POST);
    $formHandledData = authUser($connection, $data);
}


$layoutContent = include_template('index.php', ['title' => $title, 'data' => $formHandledData['data'], 'errors' => $formHandledData['errors']]);

print $layoutContent;