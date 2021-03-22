<?php

require_once 'functions/db.php';
require_once 'bootstrap.php';
require_once 'functions/template.php';
require_once 'functions/request.php';
require_once 'functions/filters.php';
require_once 'functions/validators/validate-form-text.php';
require_once 'functions/validators/validate-form-quote.php';
require_once 'functions/validators/validate-form-photo.php';
require_once 'functions/validators/validate-form-video.php';
require_once 'functions/validators/validate-form-link.php';
require_once 'functions/actions.php';


//Todo: Имена полей в форме должны соответствовать полям в бд
//Todo: Убрать if и сделать один switch case на всю страница
//Todo: создать файл validation и перенести туда функции валидации из request
//Todo: изучить вопросы автоматического тестирования (php unit или kahlan)
//Todo: connect переименовать в connecttion

$title = 'Создание поста';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$formHandledData['errors'] = [];
$formHandledData['data'] = [];

$contentType = getContentTypes($connection);

$contentTypeId = getIdFromParams($_GET) ?? 1;

const CONTENT_TYPE_TEXT = 1;
const CONTENT_TYPE_QUOTE = 2;
const CONTENT_TYPE_PHOTO = 3;
const CONTENT_TYPE_VIDEO = 4;
const CONTENT_TYPE_LINK = 5;

if (isset($_POST['submit'])) {

    switch ($_POST['submit']) {
        case CONTENT_TYPE_TEXT:
            $formHandledData = addText($connection, $_POST);
            break;
        case CONTENT_TYPE_QUOTE:
            $formHandledData = addQuote($connection, $_POST);
            break;
        case CONTENT_TYPE_PHOTO:
            $formHandledData = addPhoto($connection, $_POST, $_FILES);
            break;
        case CONTENT_TYPE_VIDEO:
            $formHandledData = addVideo($connection, $_POST);
            break;
        case CONTENT_TYPE_LINK:
            $formHandledData = addLink($connection, $_POST);
            break;
    }
}

$formTemplate = getFormTemplate($contentTypeId);
$formContent = include_template($formTemplate , ['errors' => $formHandledData['errors'], 'data' => $formHandledData['data'], 'id' => $contentTypeId]);

$mainContent = include_template('adding-post.php', ['formContent' => $formContent, 'contentType' => $contentType, 'id' => $contentTypeId]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;