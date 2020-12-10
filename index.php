<?php

require_once 'helpers.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$posts = getPosts($connect);

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['posts' => $posts]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
