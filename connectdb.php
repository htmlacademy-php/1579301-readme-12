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

    $sqlPost = 'SELECT post.content, post.picture, post.link, post.header, post.create_time, user.login, user.avatar, content_type.class_icon FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id order by `count_views` LIMIT 6';
    $resultPost = mysqli_query($connect, $sqlPost);
    $rowsPost = mysqli_fetch_all($resultPost, MYSQLI_ASSOC);

    var_export($rowsPost);
}
