<?php

/**
 * Добавляет текстовый пост на страницу
 * @param $connection - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function addText(mysqli $connection, array $data):array
{
    $data = filterFormText($data);

    $errors = validateFormText($data);

    if (!$errors) {
        addPost($connection, $data);
        $lastPostId = mysqli_insert_id($connection);

        if (!empty(trim($_POST['hashtag']))) {
            $hashtagArray = hashtagArray(trim($_POST['hashtag']));
            addHashtag($connection, $hashtagArray, $lastPostId);
        }
        header('Location: /post.php?id='.$lastPostId);
        exit();
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}

/**
 * Добавляет пост-цитату на страницу
 * @param $connection - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */

function addQuote(mysqli $connection, array $data):array
{
    $data = filterFormQuote($data);

    $errors = validateFormQuote($data);

    if (!$errors) {
        addPost($connection, $data);
        $lastPostId = mysqli_insert_id($connection);

        if (!empty(trim($_POST['hashtag']))) {
            $hashtagArray = hashtagArray(trim($_POST['hashtag']));
            addHashtag($connection, $hashtagArray, $lastPostId);
        }
        header('Location: /post.php?id='.$lastPostId);
        exit();
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}

/**
 * Добавляет пост-фото на страницу
 * @param $connection - подключение к бд
 * @param array $dataPost - глобальный массив $_POST
 * @param array $dataFiles - - глобальный массив $_FILES
 * @return array
 */
function addPhoto(mysqli $connection, array $dataPost, array $dataFiles)
{
    $data = filterFormPhoto($dataPost, $dataFiles);

    $errors = validateFormPhoto($dataPost, $dataFiles);

    if (!$errors) {

        if (!empty($dataFiles['photo']['name'])) {
            uploadPhoto($data);
        } else {
            $data['photo']['name'] = uploadPhotoUrl($data);
        }

        addPost($connection, $data);
        $lastPostId = mysqli_insert_id($connection);

        if (!empty(trim($data['hashtag']))) {
            $hashtagArray = hashtagArray(trim($data['hashtag']));
            addHashtag($connection, $hashtagArray, $lastPostId);
        }
        header('Location: /post.php?id='.$lastPostId);
        exit();
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}

/**
 * Добавляет пост-видео на страницу
 * @param $connection - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function addVideo(mysqli $connection, array $data)
{
    $data = filterFormVideo($data);

    $errors = validateFormVideo($data);

    if (!$errors) {
        $data['videoCover'] = embed_youtube_cover($data['video']);

        addPost($connection, $data);
        $lastPostId = mysqli_insert_id($connection);

        if (!empty(trim($data['hashtag']))) {
            $hashtagArray = hashtagArray(trim($data['hashtag']));
            addHashtag($connection, $hashtagArray, $lastPostId);
        }
        header('Location: /post.php?id='.$lastPostId);
        exit();
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}

/**
 * Добавляет пост-ссылку на страницу
 * @param $connection - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function addLink(mysqli $connection, array $data)
{
    $data = filterFormLink($data);

    $errors = validateFormLink($data);

    if (!$errors) {

        addPost($connection, $data);
        $lastPostId = mysqli_insert_id($connection);

        if (!empty(trim($data['hashtag']))) {
            $hashtagArray = hashtagArray(trim($data['hashtag']));
            addHashtag($connection, $hashtagArray, $lastPostId);
        }
        header('Location: /post.php?id='.$lastPostId);
        exit();
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}

/**
 * Регистрирует нового пользователя в базу данных
 * @param mysqli $connection
 * @param array $dataPost - суперглобальный массив $_POST
 * @param array $dataFiles - - суперглобальный массив $_FILES
 * @return array
 */
function registrationUser(mysqli $connection, array $dataPost, array $dataFiles)
{
    $data = filterFormRegistration($dataPost, $dataFiles);

    $errors = validateFormRegistration($connection, $data);

    var_export($data);

    if (!$errors) {
        addUser($connection, $data);
    }

    if (!empty($data['avatar']['name'])) {
        uploadAvatar($data);
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}
