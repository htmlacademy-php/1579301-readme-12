<?php

/**
 * Фильтрует поля текстовой формы
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function filterFormText(array $data):array
{
    $result = [];

    $fields = [
        'header',
        'content',
        'hashtag'
    ];

    foreach ($fields as $field) {
        $result[$field] = htmlspecialchars($data[$field]) ?? '';
    }

    return $result;
}

/**
 * Фильтрует поля формы цитаты
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function filterFormQuote(array $data):array
{
    $result = [];

    $fields = [
        'header',
        'content',
        'quote-author',
        'hashtag'
    ];

    foreach ($fields as $field) {
        $result[$field] = htmlspecialchars($data[$field]) ?? '';
    }

    return $result;
}

/**
 * Фильтрует поля формы фото
 * @param $dataPost - глобальный массив $_POST
 * @param $dataFiles - глобальный массив $_FILES
 * @return array
 */
function filterFormPhoto(array $dataPost, array $dataFiles):array
{
    $result = [];

    $fields = [
        'header',
        'photo-url',
        'hashtag',
    ];

    foreach ($fields as $field) {
        $result[$field] = htmlspecialchars($dataPost[$field]) ?? '';
    }

    if (!empty($dataFiles['photo']['name'])) {
        $result['photo'] = $dataFiles['photo'];
    }

    return $result;
}

/**
 * Фильтрует поля формы видео
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function filterFormVideo(array $data):array
{
    $result =[];

    $fields = [
        'header',
        'video',
        'hashtag',
    ];

    foreach ($fields as $field) {
        $result[$field] = htmlspecialchars($data[$field] ?? '');
    }

    return $result;
}

/**
 * Фильтрует поля формы ссылка
 * @param array $data - глобальный массив $_POST
 * @return array
 */
function filterFormLink(array $data):array
{
    $result = [];

    $fields = [
        'header',
        'link',
        'hashtag',
    ];

    foreach ($fields as $field) {
        $result[$field] = htmlspecialchars($data[$field] ?? '');
    }

    return $result;
}

/**
 * Фильтрует данные формы регистрация
 * @param array $dataPost - - глобальный массив $_POST
 * @param array $dataFiles - - глобальный массив $_FILES
 * @return array
 */
function filterFormRegistration(array $dataPost,  array $dataFiles)
{
    $result = [];

    $fields = [
        'email',
        'login',
        'password',
        'password-repeat',
    ];

    foreach ($fields as $field) {
        $result[$field] = htmlspecialchars($dataPost[$field] ?? '');
    }

    if (!empty($dataFiles['avatar']['name'])) {
        $result['avatar'] = $dataFiles['avatar'];
    }

    return $result;
}
