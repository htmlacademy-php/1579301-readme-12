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

$errors = [];

$contentType = getContentTypes($connect);

$contentTypeId = getIdFromParams($_GET) ?? 1;

const CONTENT_TYPE_TEXT = 1;
const CONTENT_TYPE_QUOTE = 2;
const CONTENT_TYPE_PHOTO = 3;
const CONTENT_TYPE_LINK = 5;
const CONTENT_TYPE_VIDEO = 4;

if (isset($_POST['submit'])) {

    switch ($_POST['submit']) {
        case CONTENT_TYPE_TEXT:
            extract(addText($connect, $_POST));
            break;
        case CONTENT_TYPE_QUOTE:
            extract(addQuote($connect, $_POST));
            break;
        case CONTENT_TYPE_PHOTO:
            extract(addPhoto($connect, $_POST, $_FILES));
            break;
        case CONTENT_TYPE_LINK:
            extract(addLink($connect, $_POST));
            break;
        case CONTENT_TYPE_VIDEO:
            extract(addVideo($connect, $_POST));
            break;
    }
}

switch ($contentTypeId) {
    case CONTENT_TYPE_TEXT:
        $formContent = include_template('add-post-text.php', ['errors' => $errors, 'id' => CONTENT_TYPE_TEXT]);
        break;
    case CONTENT_TYPE_QUOTE:
        $formContent = include_template('add-post-quote.php', ['errors' => $errors, 'id' => CONTENT_TYPE_QUOTE]);
        break;
    case CONTENT_TYPE_PHOTO:
        $formContent = include_template('add-post-photo.php', ['errors' => $errors, 'id' => CONTENT_TYPE_PHOTO]);
        break;
    case CONTENT_TYPE_VIDEO:
        $formContent = include_template('add-post-video.php', ['errors' => $errors, 'id' => CONTENT_TYPE_LINK]);
        break;
    case CONTENT_TYPE_LINK:
        $formContent = include_template('add-post-link.php', ['errors' => $errors, 'id' => CONTENT_TYPE_VIDEO]);
        break;
}

$mainContent = include_template('adding-post.php', ['formContent' => $formContent, 'contentType' => $contentType, 'id' => $contentTypeId]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;