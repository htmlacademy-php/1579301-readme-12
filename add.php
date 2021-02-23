<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$contentType = getContentTypes($connect);

$id = getIdFromParams($_GET) ?? 1;

$errors = [];

$quoteContent = NULL;

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

    if (!empty($_FILES['photo']['name'])) {

        $allowedPics = ['image/png', 'image/jpeg', 'image/gif'];

        if (in_array($_FILES['photo']['type'], $allowedPics)) {

            var_export($_FILES);
            $fileName = $_FILES['photo']['name'];
            $filePath = __DIR__ . '/uploads/';
            $fileUrl = '/uploads/' . $fileName;

            move_uploaded_file($_FILES['photo']['tmp_name'], $filePath . $fileName);

            echo $fileName;
        } else {
            $errors['mimeType'] = 'Разрешены только форматы png, jpeg, gif';
        }
    } elseif (!empty($_POST['photo-url'])) {

        $filterUrl = filter_var($_POST['photo-url'], FILTER_VALIDATE_URL);

         if ($filterUrl) {

             $allowedPics = ['jpg', 'jpeg', 'png', 'gif'];

             $fileName = basename(parse_url($filterUrl, PHP_URL_PATH));
             $mime = pathinfo($fileName, PATHINFO_EXTENSION);

             if (in_array($mime, $allowedPics)) {
                 
                 $downloadFile = file_get_contents($filterUrl);
                 $filePath = __DIR__ . '/uploads/';
                 file_put_contents($filePath . $fileName, $downloadFile);
             } else {
                 $errors['photo'] = 'Ссылка должна заказчиваться на .jpg, .jpeg, .png, .gif';
             }
         } else {
             $errors['photo'] = 'Необходимо указать корректную ссылку';
         }
    }

    if ($_POST['submit'] === 'link') {

        if (!empty($_POST['post-link'])) {

            $filterUrl = filter_var($_POST['post-link'], FILTER_VALIDATE_URL);

            if ($filterUrl) {
                $link = $filterUrl;
            } else {
                $errors['link'] = 'Ссылка должна быть корректным значением';
            }
        } else {
            $errors['link'] = 'Сыылка должна быть указана';
        }
    }

    if ($_POST['submit'] === 'video') {

        if (!empty($_POST['video'])) {

            $filterUrl = filter_var($_POST['video'], FILTER_VALIDATE_URL);

            if ($filterUrl) {

                if (check_youtube_url($filterUrl) === true) {
                    $video = $filterUrl;
                    $videoCover = embed_youtube_cover($filterUrl);
                    echo $videoCover;

                } else {
                    $errors['video'] = check_youtube_url($filterUrl);
                }

            } else {
                $errors['video'] = 'Ссылка на youtube должна быть корретной';
            }

        } else {
            $errors['video'] = 'Ссылка на видео должна быть заполнена';
        }
    }

    $criteria = [
        'header' => $header ?? NULL,
        'content' => $content ?? $quoteContent,
        'quote-author' => $quoteAuthor ?? NULL,
        'contentTypeId' => $id,
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