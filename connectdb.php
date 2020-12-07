<?php

$host = 'localhost';
$login = 'root';
$password = 'root';
$bdname = 'readme';

$connect = mysqli_connect($host, $login, $password, $bdname);

mysqli_set_charset($connect, "utf8");

if (!$connect) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
else {
    print("Соединение установлено");

}
