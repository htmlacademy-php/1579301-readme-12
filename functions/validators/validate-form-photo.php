<?php

/**
 * Проверяет введенные данные для формы цитаты
 * @param $data - входящий суперглобальный массив $_POST
 * @return array
 */
function validateFormPhoto(array $data, array $files)
{
    $errors = [];

    if ($error = validateFormPhotoHeader($data['header'])) {
        $errors['header'] = $error;
    }

    if ($error = validateFormPhotoUrl($data['photo-url'])) {
        $errors['photo-url'] = $error;
    }

    if ($error = validateFormPhotoFile($files['photo']['type'])) {
        $errors['photo'] = $error;
    }

    return $errors;
}

/**
 * Проверяет заголовок поста
 * @param $header - заголовок из массива $_POST
 * @return string|null
 */
function validateFormPhotoHeader($header)
{
    if (empty($header)) {
        return 'Строка не может быть пустой';
    }

    if  (mb_strlen($header) > 50) {
        return 'Более 50 символов';
    }

    return NULL;
}

/**
 * Проверяет тело поста
 * @param $content - текст поста из массива $_POST
 * @return string
 */
function validateFormPhotoUrl($photoUrl)
{
    if (!empty($photoUrl)) {

        $filterUrl = filter_var($photoUrl, FILTER_VALIDATE_URL);

        if ($photoUrl) {

            $allowedPics = ['jpg', 'jpeg', 'png', 'gif'];

            $fileName = basename(parse_url($filterUrl, PHP_URL_PATH));
            $mime = pathinfo($fileName, PATHINFO_EXTENSION);

            if (!in_array($mime, $allowedPics)) {
                return 'Ссылка должна заказчиваться на .jpg, .jpeg, .png, .gif';
            }
        } else {
            return 'Необходимо указать корректную ссылку';
        }
    }

    return null;
}

function validateFormPhotoFile($mimeType)
{
    $allowedPics = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];

    if (!empty($mimeType) && !in_array($mimeType, $allowedPics)) {
        return 'Разрешены только форматы png, jpeg, gif';
    }

    return NULL;
}


