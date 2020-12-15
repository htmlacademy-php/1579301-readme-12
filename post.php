<?php

require_once 'helpers.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$title = 'публикация';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$mainContent = include_template('posts-details.php', []);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
