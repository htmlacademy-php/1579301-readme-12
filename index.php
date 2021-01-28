<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$id = getIdFromParams($_GET);

$posts = getPosts($connect, $id);

$contentType = getContentType($connect);

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['posts' => $posts, 'contentType' => $contentType, 'id' => $id]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
