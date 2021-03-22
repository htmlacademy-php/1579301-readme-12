<?php

/**
 * Проверяет введенные данные для формы цитаты
 * @param $data - входящий суперглобальный массив $_POST
 * @return array
 */
function validateFormQuote($data)
{
    $errors = [];

    if ($error = validateFormQuoteHeader($data['header'])) {
        $errors['header'] = $error;
    }

    if ($error = validateFormQuoteContent($data['content'])) {
        $errors['content'] = $error;
    }

    if ($error = validateFormQuoteAuthor($data['quote-author'])) {
        $errors['quote-author'] = $error;
    }

    return $errors;
}

/**
 * Проверяет заголовок поста
 * @param $header - заголовок из массива $_POST
 * @return string|null
 */
function validateFormQuoteHeader($header)
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
function validateFormQuoteContent($content)
{
    if (empty($content)) {
        return 'Цитата должна быть заполнена';
    }

    if  (mb_strlen($content) > 35) {
        return 'Цитата не может привышать 70 символов';
    }

    return NULL;
}

/**
 * Проверяет автора поста
 * @param $quoteAuthor - автор поста из массива $_POST
 * @return string|null
 */
function validateFormQuoteAuthor($quoteAuthor)
{
    if (empty($quoteAuthor)) {
        return 'Автор должен быть указан';
    }

    return NULL;
}
