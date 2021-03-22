<?php

/**
 * Проверяет введенные данные для формы видео
 * @param array $data - входящий суперглобальный массив $_POST
 * @return array
 */
function validateFormVideo(array $data)
{
    $errors = [];

    if ($error = validateFormVideoHeader($data['header'])) {
        $errors['header'] = $error;
    }

    if ($error = validateFormVideoUrl($data['video'])) {
        $errors['video'] = $error;
    }

    return $errors;
}

/**
 * Проверяет заголовок поста
 * @param $header - заголовок из массива $_POST
 * @return string|null
 */
function validateFormVideoHeader($header)
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
 * Проверяет валидность введенной ссылки
 * @param $videoUrl - ссылка на видео из массива $_POST
 * @return string|null
 */
function validateFormVideoUrl($videoUrl)
{
    if (!empty($videoUrl)) {

        $filterUrl = filter_var($videoUrl, FILTER_VALIDATE_URL);

        if ($filterUrl) {

            if (check_youtube_url($filterUrl) !== true) {
                return check_youtube_url($filterUrl);
            }

        } else {
            return 'Ссылка на youtube должна быть корретной';
        }

    } else {
        return 'Ссылка на видео должна быть заполнена';
    }

    return NULL;
}
