<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/template.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/request.php';

$config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$connect = dbConnect($config['db']);

$id = $_GET['postid'];

switch($_GET['action']) {
    case 'like':
        updateLikesCount($connect, $id);
        header('Location: http://1579301-readme-12/post.php?id='.$id);
        break;
}
