<?php

/**
 * Подключает к базе данных
 * @param array $dbParams
 * @return mysqli
 */
function dbConnect(array $dbParams) : mysqli
{
    $connect = mysqli_connect($dbParams['host'], $dbParams['user'], $dbParams['password'], $dbParams['database']);

    if (!$connect) {
        exit("Ошибка подключения: " . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");
    return $connect;
}
