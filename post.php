<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';

$config = require 'config.php';

$connect = dbConnect($config['db']);

$contentType = getContentType($connect);

$id = getIdFromParams($_GET);

$isPostIsset = isPostIsset($connect, $id);

updateViewsCount($connect, $id);

$post = getPost($connect, $id);

$postCreatorId = $post['user_id'];

$validMsg = true;

if (!empty($_POST)) {
    $validMsg = validateComment($_POST);
    addComment($connect, $id, $postCreatorId, $_POST);
}

$commentData = getCommentData($connect, $id);

$countSubscribers = countSubscribers($connect, $id);

$countPosts = countPosts($connect, $id);

$title = 'публикация';

$is_auth = rand(0, 1);

$user_name = 'Dima'; // укажите здесь ваше имя

switch ($post['class_icon']) {
    case 'post-text':
        $postContent = include_template('post-text.php', ['post' => $post]);
        break;
    case 'post-quote':
        $postContent = include_template('post-quote.php', ['post' => $post]);
        break;
    case 'post-photo':
        $postContent = include_template('post-photo.php', ['post' => $post]);
        break;
    case 'post-video':
        $postContent = include_template('post-video.php', ['post' => $post]);
        break;
    case 'post-link':
        $postContent = include_template('post-link.php', ['post' => $post]);
        break;
}

$postLayout = include_template('post-details.php', ['postContent' => $postContent, 'post' => $post, 'commentData' => $commentData, 'validMsg' => $validMsg, 'countSubscribers' => $countSubscribers, 'countPosts' => $countPosts]);

$layoutContent = include_template('layout.php', ['mainContent' => $postLayout, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
