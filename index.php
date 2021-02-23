<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$id = getIdFromParams($_GET);

$sort = $_GET;
$sort['order'] = $sort['order'] ?? '';
$sort['sort'] = $sort['sort'] ?? '';

$order = (($sort['order'] == 'desc') ? 'asc' : 'desc');

$totalPosts = getCountPosts($connect, $id);

/*$productsOnPage = 6; // Желаемое количество товаров на странице*/
$currentPage = $_GET['page'] ?? 1; // Извлекаем из URL текущую страницу
$totalPages = ceil($totalPosts / $config['pagination']['postsOnPage']); // Общее число страниц
$start = $currentPage * $config['pagination']['postsOnPage'] - $config['pagination']['postsOnPage']; // Вычисляем с какого номера необходимо выводить сообщение

$criteria = [
    'contentTypeId' => $id,
    'sort' => [
        'type' => $sort['sort'],
        'order' => $order
    ],
    'pagination' => [
        'postsOnPage' => $config['pagination']['postsOnPage'],
        'currentPage' => $currentPage,
        'startItem' => $start,
        'totalPosts' => $totalPosts,
        'totalPages' => $totalPages
    ]
];

$posts = getPosts($connect, $criteria);

$contentType = getContentTypes($connect);

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['posts' => $posts, 'contentType' => $contentType, 'criteria' => $criteria]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
