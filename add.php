<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';
require_once 'bootstrap.php';

$contentType = getContentTypes($connect);

$contentTypeId = getIdFromParams($_GET) ?? 1;

$errors = [];

$quoteContent = NULL;

if (isset($_POST['submit'])) {

    if ($_POST['submit'] === 'text') {

        $errors = validateFormText($_POST);

        if (!$errors) {
            $content = $_POST['post-text'];
            $header = $_POST['header'];
        }
    }

    if ($_POST['submit'] === 'quote') {

        $errors = validateFormQuote($_POST);

        if (!$errors) {
            $quoteContent = $_POST['quote-text'];
            $quoteAuthor = $_POST['quote-author'];
            $header = $_POST['header'];
        }
    }

    if (!empty($_FILES['photo']['name'])) {

        $errors = validateFormPhoto($_POST, $_FILES);

        if (!$errors) {
            uploadPhoto($_FILES);
            $fileName = $_FILES['photo']['name'];
            $header = $_POST['header'];
        }

    } elseif (isset($_POST['photo-url'])){

        $errors = validateFormPhoto($_POST, $_FILES);

        if (!$errors) {
            $fileName = basename(parse_url($_POST['photo-url'], PHP_URL_PATH));
            $header = $_POST['header'];
        }
    }

    if ($_POST['submit'] === 'link') {

        $errors = validateFormLink($_POST);

        if (!$errors) {
            $link = $_POST['post-link'];
            $header = $_POST['header'];
        }
    }

    if ($_POST['submit'] === 'video') {

        $errors = validateFormVideo($_POST);

        if (!$errors) {
            $video = $_POST['video'];
            $videoCover = embed_youtube_cover($_POST['video']);
            $header = $_POST['header'];
        }
    }

    $criteria = [
        'header' => $header ?? NULL,
        'content' => $content ?? $quoteContent,
        'quote-author' => $quoteAuthor ?? NULL,
        'contentTypeId' => $contentTypeId,
        'picture' => $fileName ?? NULL,
        'link' => $link ?? NULL,
        'video' => $video ?? NULL,
        'videoCover' => $videoCover ?? NULL,
    ];

    if (!count($errors)) {

        addPost($connect, $criteria);

        if (!empty(trim($_POST['hashtag']))) {
            $hashtagArray = hashtagArray(trim($_POST['hashtag']));
            var_export($hashtagArray);
            addHashtag($connect, $hashtagArray, mysqli_insert_id($connect));
        }
    }
}

$title = 'Создание поста';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

switch ($contentTypeId) {
    case '1':
        $formContent = include_template('add-post-text.php', ['errors' => $errors, 'id' => $contentTypeId]);
        break;
    case '2':
        $formContent = include_template('add-post-quote.php', ['errors' => $errors, 'id' => $contentTypeId]);
        break;
    case '3':
        $formContent = include_template('add-post-photo.php', ['errors' => $errors, 'id' => $contentTypeId]);
        break;
    case '4':
        $formContent = include_template('add-post-video.php', ['errors' => $errors, 'id' => $contentTypeId]);
        break;
    case '5':
        $formContent = include_template('add-post-link.php', ['errors' => $errors, 'id' => $contentTypeId]);
        break;
}

$mainContent = include_template('adding-post.php', ['formContent' => $formContent, 'contentType' => $contentType, 'id' => $contentTypeId]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;