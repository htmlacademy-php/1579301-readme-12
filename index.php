<?php

require_once 'bootstrap.php';

if (authBySession($_SESSION)) {
    header('location:' . 'feed.php');
}

$title = 'Регистрация и аутентификация';

$formHandledData['errors'] = [];
$formHandledData['data'] = [];

if (!empty($_POST)) {
    $data = filterFormAuth($_POST);
    $formHandledData = authUser($connection, $data);
}

$layoutContent = include_template('index.php', ['title' => $title, 'data' => $formHandledData['data'], 'errors' => $formHandledData['errors']]);

print $layoutContent;