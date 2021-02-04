<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$id = getIdFromParams($_GET);

$sort = $_GET;

$sort['order'] = $sort['order'] ?? '';

$order = (($sort['order'] == 'asc') ? 'desc' : 'asc');

$totalPosts = getCountPosts($connect);

$productsOnPage = 6; // Желаемое количество товаров на странице
$currentPage = $_GET['page'] ?? 1; // Извлекаем из URL текущую страницу
$total = ceil($totalPosts / $productsOnPage); // Общее число страниц
$start = $currentPage * $productsOnPage - $productsOnPage; // Вычисляем с какого номера необходимо выводить сообщение

$posts = getPosts($connect, $id, $sort, $order, $start);

var_export($totalPosts);

$contentType = getContentType($connect);

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['posts' => $posts, 'contentType' => $contentType, 'id' => $id, 'order' => $order, 'sort' => $sort, 'currentPage' => $currentPage]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
