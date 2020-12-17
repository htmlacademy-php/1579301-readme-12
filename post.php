<?php

require_once 'helpers.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$contentType = getContentType($connect);

$id = getIdFromParams($_GET);

$posts = getPosts($connect, null);

var_export($posts);

$title = 'публикация';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$postContent = include_template('post-photo.php', ['posts' => $posts, 'id' => $id]);

$postLayout = include_template('post-details.php', ['postContent' => $postContent, 'posts' => $posts, 'id' => $id, 'contentType' => $contentType]);

$layoutContent = include_template('layout.php', ['mainContent' => $postLayout, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
