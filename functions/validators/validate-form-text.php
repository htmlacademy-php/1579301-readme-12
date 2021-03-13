<?php

/**
 * Проверяет введенные данные для текстовой формы записи
 * @param $data - входящий суперглобальный массив $_POST
 * @return array|string
 */
function validateFormText($data)
{
    $errors = [];

    if ($error = validateFormTextHeader($data['header'])) {
        $errors['header'] = $error;
    }

    if ($error = validateFormTextContent($data['content'])) {
        $errors['content'] = $error;
    }

    return $errors;
}

/**
 * Проверяет заголовок поста
 * @param $header - заголовок из массива $_POST
 * @return string|null
 */
function validateFormTextHeader($header)
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
function validateFormTextContent($content)
{
    if (empty($content)) {
        return 'Текст публикации не может быть пустым';
    }

    return NULL;
}
