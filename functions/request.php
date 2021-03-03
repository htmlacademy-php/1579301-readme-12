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
 * Проверяет введенные данные для текстовой формы записи
 * @param $data - входящий суперглобальный массив $_POST
 * @return array|string
 */
function validateFormText($data)
{
    $errors = [];

    if (empty($data['header'])) {
        $errors['header'] = 'Заголовок. Это поле должно быть заполнено.';
    }

    if (empty($data['post-text'])) {
        $errors['content'] = 'Вы не заполнили текст поста!';
    }
    return $errors;
}

/**
 * Проверяет введенные данные для формы цытаты
 * @param $data - входящий суперглобальный массив $_POST
 * @return array
 */
function validateFormQuote($data)
{
    $errors = [];

    if (empty($data['header'])) {
        $errors['header'] = 'Заголовок. Это поле должно быть заполнено.';
    }

    if (empty($data['quote-text']) || mb_strlen($data['quote-text']) > 70) {
        $errors['quote-content'] = 'Цитата. Должна быть заполнена. Она не должна привышать 70 знаков';
    }

    if (empty($data['quote-author'])) {
        $errors['quote-author'] = 'Автор должен быть указан';
    }
    return $errors;
}

/**
 * Проверяет введенные данные для формы ссылка
 * @param $data - входящий суперглобальный массив $_POST
 * @return array
 */
function validateFormLink($data)
{
    $errors = [];

    if (empty($data['header'])) {
        $errors['header'] = 'Заголовок. Это поле должно быть заполнено.';
    }

    if (!empty($_POST['post-link'])) {
        $filterUrl = filter_var($_POST['post-link'], FILTER_VALIDATE_URL);

        if (!$filterUrl) {
            $errors['link'] = 'Ссылка должна быть корректным значением';
        }

    } else {
        $errors['link'] = 'Сыылка должна быть указана';
    }

    return $errors;
}

/**
 * Проверяет введенные данные для формы видео
 * @param $data - входящий суперглобальный массив $_POST
 * @return array
 */
function validateFormVideo($data)
{
    $errors = [];

    if (empty($data['header'])) {
        $errors['header'] = 'Заголовок. Это поле должно быть заполнено.';
    }

    if (!empty($data['video'])) {

        $filterUrl = filter_var($data['video'], FILTER_VALIDATE_URL);

        if ($filterUrl) {

            if (check_youtube_url($filterUrl) !== true) {
                $errors['video'] = check_youtube_url($filterUrl);
            }

        } else {
            $errors['video'] = 'Ссылка на youtube должна быть корретной';
        }

    } else {
        $errors['video'] = 'Ссылка на видео должна быть заполнена';
    }

    return $errors;
}

/**
 * Проверяет введенные данные для формы фото
 * @param $dataPost - входящий суперглобальный массив $_POST
 * @param $dataFiles - входящий суперглобальный массив $_FILES
 * @return array
 */
function validateFormPhoto($dataPost, $dataFiles)
{
    $errors = [];

    if (empty($dataPost['header'])) {
        $errors['header'] = 'Заголовок. Это поле должно быть заполнено.';
    }

    if (!empty($dataPost['photo-url'])) {

        $filterUrl = filter_var($dataPost['photo-url'], FILTER_VALIDATE_URL);

        if ($filterUrl) {

            $allowedPics = ['jpg', 'jpeg', 'png', 'gif'];

            $fileName = basename(parse_url($filterUrl, PHP_URL_PATH));
            $mime = pathinfo($fileName, PATHINFO_EXTENSION);

            if (in_array($mime, $allowedPics)) {

                $downloadFile = file_get_contents($filterUrl);
                $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
                file_put_contents($filePath . $fileName, $downloadFile);
            } else {
                $errors['photo'] = 'Ссылка должна заказчиваться на .jpg, .jpeg, .png, .gif';
            }
        } else {
            $errors['photo'] = 'Необходимо указать корректную ссылку';
        }
    } elseif (empty($dataFiles['photo']['name'])) {
        $errors['photo'] = 'Необходимо заполнить поле';
    }

    if (!empty($dataFiles['photo']['name'])) {

        $allowedPics = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];

        if (!in_array($dataFiles['photo']['type'], $allowedPics)) {
            $errors['mimeType'] = 'Разрешены только форматы png, jpeg, gif';
        }
    }

    return $errors;
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
