<?php

require_once 'bootstrap.php';

if (!isAuth($_SESSION)) {
    header('location:' . '/');
}

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'Регистрация и аутентификация';

$feedContent = include_template('feed.php', []);

$layoutContent = include_template('layout.php', ['mainContent' => $feedContent, 'title' => $title, 'is_auth' => isAuth($_SESSION), 'user_name' => $user_name]);

print $layoutContent;