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
