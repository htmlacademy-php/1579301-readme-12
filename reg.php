<?php

require_once 'functions/db.php';
require_once 'bootstrap.php';
require_once 'functions/template.php';
require_once 'functions/request.php';
require_once 'functions/filters.php';
require_once 'functions/validators/validate-form-registration.php';
require_once 'functions/actions.php';

$formHandledData['errors'] = [];
$formHandledData['data'] = [];

if (!empty($_POST)) {
    var_export($_FILES);
    $formHandledData = registrationUser($connection, $_POST, $_FILES);
}


$title = 'Создание поста';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя


$registrationContent = include_template('registration.php', ['errors' => $formHandledData['errors'], 'data' => $formHandledData['data']]);

$layoutContent = include_template('layout.php', ['mainContent' => $registrationContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;