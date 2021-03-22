<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/template.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/request.php';

$config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$connection = dbConnect($config['db']);

$id = getIdFromParams($_GET);

switch($_GET['action']) {
    case 'like':
        updateLikesCount($connection, $id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        break;
}

/*http://1579301-readme-12/post.php?id='.$id*/