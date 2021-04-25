<?php

require_once 'bootstrap.php';

$userData = [];

$id = getIdFromParams($_GET);

if (!authBySession($_SESSION)) {
    header('location:' . '/');
} else {
    $userData = getUserById($connection , $_SESSION['user_id']);
    $subscribedPosts = getSubscribedPosts($connection, $_SESSION['user_id'], $id);
}

$title = 'Регистрация и аутентификация';

$contentType = getContentTypes($connection);

$feedContent = include_template('feed.php', ['id' => $id, 'contentType' => $contentType, 'subscribedPosts' => $subscribedPosts]);

$layoutContent = include_template('layout.php', ['mainContent' => $feedContent, 'title' => $title, 'is_auth' => authBySession($_SESSION), 'userData' => $userData]);

print $layoutContent;