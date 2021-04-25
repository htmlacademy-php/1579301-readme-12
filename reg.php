<?php

require_once 'bootstrap.php';


$formHandledData['errors'] = [];
$formHandledData['data'] = [];

if (!empty($_POST)) {
    $data = filterFormRegistration($_POST, $_FILES);
    $formHandledData = registrationUser($connection, $data);
}

$title = 'Создание поста';

$registrationContent = include_template('registration.php', ['errors' => $formHandledData['errors'], 'data' => $formHandledData['data']]);

$layoutContent = include_template('layout.php', ['mainContent' => $registrationContent, 'title' => $title, 'is_auth' => authBySession($_SESSION)]);

print $layoutContent;
