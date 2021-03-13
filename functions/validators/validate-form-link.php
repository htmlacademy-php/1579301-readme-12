<?php

function validateFormLink(array $data)
{
    $errors = [];

    if ($error = validateFormLinkHeader($data['header'])) {
        $errors['header'] = $error;
    }

    if ($error = validateFormLinkUrl($data['link'])) {
        $errors['link'] = $error;
    }

    return $errors;
}

/**
 * Проверяет заголовок поста
 * @param $header - заголовок из массива $_POST
 * @return string|null
 */
function validateFormLinkHeader($header)
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
 * @param $link - ссылка из массива $_POST
 * @return string|null
 */
function validateFormLinkUrl($link)
{
    if (!empty($link)) {
        $filterUrl = filter_var($link, FILTER_VALIDATE_URL);

        if (!$filterUrl) {
            return 'Ссылка должна быть корректным значением';
        }

    } else {
        return 'Сыылка должна быть указана';
    }

    return NULL;
}
