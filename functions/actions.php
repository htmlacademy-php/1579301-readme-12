<?php

/**
 * Добавляет текстовый пост на страницу
 * @param $connection - подключение к бд
 * @param array $data - отфильтрованный массив $_POST
 * @return array
 */
function addText(mysqli $connection, array $data):array
{
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
 * @param array $data - отфильтрованный массив $_POST
 * @return array
 */

function addQuote(mysqli $connection, array $data):array
{
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
 * @param array $data - отфильтрованный глобальный массив $_POST и $_FILES
 * @return array
 */
function addPhoto(mysqli $connection, array $data)
{
    $errors = validateFormPhoto($data);

    if (!$errors) {

        if (!empty($data['photo']['name'])) {
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
 * @param array $data - отфильтрованный глобальный массив $_POST
 * @return array
 */
function addVideo(mysqli $connection, array $data)
{
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
 * @param array $data - отфильтрованный глобальный массив $_POST
 * @return array
 */
function addLink(mysqli $connection, array $data)
{
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
 * @param array $filteredData - отфильтрованный массив $_POST и $_FILES
 * @return array
 */
function registrationUser(mysqli $connection, array $filteredData)
{
    $errors = validateFormRegistration($connection, $filteredData);

    if (!$errors) {
        addUser($connection, $filteredData);
    }

    if (!empty($filteredData['avatar']['name'])) {
        uploadAvatar($filteredData);
    }

    return [
        'data' => $filteredData,
        'errors' => $errors,
    ];
}
