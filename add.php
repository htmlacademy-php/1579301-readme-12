<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$contentType = getContentTypes($connect);

$id = getIdFromParams($_GET) ?? 1;

$errors = [];

$quoteContent = '';

if (isset($_POST['submit'])) {

    if (!empty($_POST['header'])) {
        $header = $_POST['header'];
    } else {
        $errors['header'] = 'Заголовок. Это поле должно быть заполнено.';
    }

    if ($_POST['submit'] === 'text') {

        if (!empty($_POST['post-text'])) {
            $content = $_POST['post-text'];
        } else {
            $errors['content'] = 'Вы не заполнили текст поста!';
        }
    }

    if ($_POST['submit'] === 'quote') {

        if (!empty($_POST['quote-text']) && mb_strlen($_POST['quote-text']) < 70) {
            $quoteContent = $_POST['quote-text'];
        } else {
            $errors['quote-content'] = 'Цитата. Должна быть заполнена. Она не должна привышать 70 знаков';
        }

        if (!empty($_POST['quote-author'])) {
            $quoteAuthor = $_POST['quote-author'];
        } else {
            $errors['quote-author'] = 'Автор должен быть указан';
        }
    }


    if (isset($_FILES['photo'])) {

        var_export($_FILES);
        $fileName = $_FILES['photo']['name'];
        $filePath = __DIR__ . '/uploads/';
        $fileUrl = '/uploads/' . $fileName;

        move_uploaded_file($_FILES['photo']['tmp_name'], $filePath . $fileName);

        print("<a href='$fileUrl'>$fileName</a>");
    }


    $criteria = [
        'header' => $header ?? NULL,
        'content' => $content ?? $quoteContent,
        'quote-author' => $quoteAuthor ?? NULL,
        'contentTypeId' => $id,
    ];

    var_export($_POST);

    addPostText($connect, $criteria);

    if (!empty(trim($_POST['hashtag']))) {
        $hashtagArray = hashtagArray(trim($_POST['hashtag']));
        var_export($hashtagArray);
        addHashtag($connect, $hashtagArray, mysqli_insert_id($connect));
    }
}




$title = 'Создание поста';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

switch ($id) {
    case '1':
        $formContent = include_template('add-post-text.php', ['errors' => $errors, 'id' => $id]);
        break;
    case '2':
        $formContent = include_template('add-post-quote.php', ['errors' => $errors, 'id' => $id]);
        break;
    case '3':
        $formContent = include_template('add-post-photo.php', ['errors' => $errors, 'id' => $id]);
        break;
    case '4':
        $formContent = include_template('add-post-video.php', ['errors' => $errors, 'id' => $id]);
        break;
    case '5':
        $formContent = include_template('add-post-link.php', ['errors' => $errors, 'id' => $id]);
        break;
}

$mainContent = include_template('adding-post.php', ['formContent' => $formContent, 'contentType' => $contentType, 'id' => $id]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;