<?php

/**
 * Возвращает числовой get id параметр приведенный
 * @param array $params
 * @return int|null
 */
function getIdFromParams(array $params) : ?int
{
    if (!isset($params['id'])) {
        return null;
    }

    if (! is_numeric($params['id'])) {
        exit('Неверный параметр в запросе');
    }

    return (int) $params['id'];
}

/**
 * Считает количество символов при отправке комментария
 * @param array $data - суперглобальный массив $_POST
 * @return bool
 */
function validateComment(array $data): bool
{
    if (!empty(isset($data['msg']) && (mb_strlen($data['msg']) >= 4))) {
            return true;
    } else {
            return false;
    }
}

/**
 * Перемещает загруженое фото в директорию сайта
 * @param $data - входящий суперглобальный массив $_FILES
 */
function uploadPhoto($data)
{
    $fileName = $data['photo']['name'];
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

    move_uploaded_file($data['photo']['tmp_name'], $filePath . $fileName);
}

/**
 * Извлекает картинку из ссылки и перемещает в директорию сайта
 * @param $data - входящий суперглобальный массив $_POST
 * @return string
 */
function uploadPhotoUrl($data)
{
    $allowedPics = ['jpg', 'jpeg', 'png', 'gif'];

    $filterUrl = filter_var($data['photo-url'], FILTER_VALIDATE_URL);

    $fileName = basename(parse_url($filterUrl, PHP_URL_PATH));
    $mime = pathinfo($fileName, PATHINFO_EXTENSION);

    if (in_array($mime, $allowedPics)) {

        $downloadFile = file_get_contents($filterUrl);
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        file_put_contents($filePath . $fileName, $downloadFile);
    }

    return $fileName;
}


