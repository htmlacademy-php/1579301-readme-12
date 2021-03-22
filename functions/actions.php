<?php

/**
 * Добавляет текстовый пост на страницу
 * @param $connect - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function addText(mysqli $connect, array $data):array
{
    $data = filterFormText($data);

    $errors = validateFormText($data);

    if (!$errors) {
        addPost($connect, $data);
        $lastPostId = mysqli_insert_id($connect);

        if (!empty(trim($_POST['hashtag']))) {
            $hashtagArray = hashtagArray(trim($_POST['hashtag']));
            addHashtag($connect, $hashtagArray, $lastPostId);
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
 * @param $connect - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */

function addQuote(mysqli $connect, array $data):array
{
    $data = filterFormQuote($data);

    $errors = validateFormQuote($data);

    if (!$errors) {
        addPost($connect, $data);
        $lastPostId = mysqli_insert_id($connect);

        if (!empty(trim($_POST['hashtag']))) {
            $hashtagArray = hashtagArray(trim($_POST['hashtag']));
            addHashtag($connect, $hashtagArray, $lastPostId);
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
 * @param $connect - подключение к бд
 * @param array $dataPost - глобальный массив $_POST
 * @param array $dataFiles - - глобальный массив $_FILES
 * @return array
 */
function addPhoto(mysqli $connect, array $dataPost, array $dataFiles)
{
    $data = filterFormPhoto($dataPost, $dataFiles);

    $errors = validateFormPhoto($dataPost, $dataFiles);

    if (!$errors) {

        if (!empty($dataFiles['photo']['name'])) {
            uploadPhoto($data);
        } else {
            $data['photo']['name'] = uploadPhotoUrl($data);
        }

        addPost($connect, $data);
        $lastPostId = mysqli_insert_id($connect);

        if (!empty(trim($data['hashtag']))) {
            $hashtagArray = hashtagArray(trim($data['hashtag']));
            addHashtag($connect, $hashtagArray, $lastPostId);
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
 * @param $connect - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function addVideo(mysqli $connect, array $data)
{
    $data = filterFormVideo($data);

    $errors = validateFormVideo($data);

    if (!$errors) {
        $data['videoCover'] = embed_youtube_cover($data['video']);

        addPost($connect, $data);
        $lastPostId = mysqli_insert_id($connect);

        if (!empty(trim($data['hashtag']))) {
            $hashtagArray = hashtagArray(trim($data['hashtag']));
            addHashtag($connect, $hashtagArray, $lastPostId);
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
 * @param $connect - подключение к бд
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function addLink(mysqli $connect, array $data)
{
    $data = filterFormLink($data);

    $errors = validateFormLink($data);

    if (!$errors) {

        addPost($connect, $data);
        $lastPostId = mysqli_insert_id($connect);

        if (!empty(trim($data['hashtag']))) {
            $hashtagArray = hashtagArray(trim($data['hashtag']));
            addHashtag($connect, $hashtagArray, $lastPostId);
        }
        header('Location: /post.php?id='.$lastPostId);
        exit();
    }

    return [
        'data' => $data,
        'errors' => $errors,
    ];
}
