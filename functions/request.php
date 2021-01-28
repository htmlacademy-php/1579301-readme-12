<?php

/**
 * Возвращает посты пользователя
 * @param mysqli $connect
 * @param int $contentTypeId
 * @return array
 */
function getPosts(mysqli $connect, ?int $contentTypeId) : array
{
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.header, post.create_time, user.login, user.avatar, content_type.class_icon FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id';
    if ($contentTypeId) {
        $sqlPost .= ' WHERE `content_type_id` = '.$contentTypeId.'';
    }
    $sqlPost .= ' order by `count_views` LIMIT 6';
    $resultPost = mysqli_query($connect, $sqlPost);
    return mysqli_fetch_all($resultPost, MYSQLI_ASSOC);
}

/**
 * Возвращает конкретный пост пользователя по заданному id
 * @param mysqli $connect
 * @param int|null $postId
 * @return array
 */
function getPost(mysqli $connect, ?int $postId): array
{
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.quote_author, post.header, post.create_time, post.user_id, post.comments_count, post.likes_count, post.count_views, user.login, user.create_time as createUser ,user.avatar, content_type.class_icon FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id WHERE post.id = ' . $postId . '';
    $resultPost = mysqli_query($connect, $sqlPost);
    return mysqli_fetch_assoc($resultPost);
}

/**
 * Возвращает тип контента
 * @param mysqli $connect
 * @return array
 */
function getContentType(mysqli $connect) : array
{
    $sqlPost = 'SELECT `id`, `class_icon`, `width_icon`, `height_icon` FROM `content_type`';
    $resultPost = mysqli_query($connect, $sqlPost);
    return mysqli_fetch_all($resultPost, MYSQLI_ASSOC);
}

function getIdFromParams(array $params) : ?int
{
    if (!isset($params['id'])) {
        return null;
    }

    if (! is_numeric($params['id'])) {
        exit('Неверный параметр в запросе');
    }

    return (int) $params['id'];
}

function getCommentData(mysqli $connect, int $postId): array
{
    $sqlPost = 'SELECT comment.create_time, comment.content, user.login, user.avatar FROM `comment` LEFT JOIN `user` ON comment.author_id = user.id WHERE comment.post_id = ' . $postId . ' ORDER BY comment.create_time DESC';
    $resultPost = mysqli_query($connect, $sqlPost);
    return mysqli_fetch_all($resultPost, MYSQLI_ASSOC);
}

/**
 * Добавляет комментарий для поста
 * @param mysqli $connect
 * @param int $postId - id текущего поста
 * @param int $postCreatorId - id создателя поста
 * @param array $data - суперглобальный массив $_POST
 */
function addComment($connect, int $postId, int $postCreatorId, array $data)
{
    date_default_timezone_set( 'Europe/Moscow' );
    $date = date("Y-m-d H:i:s");
    $msg = trim($data['msg']);
    if (mb_strlen($msg) >= 4) {
        $sql = "INSERT INTO comment (create_time, content, post_creator_id, post_id, author_id) VALUES ('$date', '$msg', '$postCreatorId', '$postId', 1)";
    } else {
        echo "Не менее 4х символов";
        return false;
    }
    $result = mysqli_query($connect, $sql);

    if (!$result) {
        echo 'ошибка';
    } else {
        updateCommentsCount($connect, $postId);
    }
}

/**
 * Обновляет общее количество комментариев для поста после каждого нового комментария
 * @param mysqli $connect
 * @param int $postId
 */
function updateCommentsCount($connect, int $postId)
{
    $sql = "SELECT COUNT(*) as count FROM comment WHERE `post_id` = '$postId'";
    $result = mysqli_query($connect, $sql);
    $currentCount = mysqli_fetch_assoc($result)['count'];

    $sql = "UPDATE post SET comments_count = '$currentCount' WHERE post.id = '$postId'";
    mysqli_query($connect, $sql);
}

/**
 * Считает количество символов при отправке комментария
 * @param array $data - суперглобальный массив $_POST
 * @return bool
 */
function validateComment(array $data): bool
{
    if (!empty(isset($data['msg']) && (mb_strlen($data['msg']) >= 4))) {
            return true;
    } else {
            return false;
    }
}

/**
 * Считает количество подписчиков для конкретного пользователя по переданному id поста
 * @param mysqli $connect
 * @param int|null $postId - id текущего поста
 * @return array
 */
function countSubscribers(mysqli $connect, ?int $postId): array
{
    $sql = 'SELECT COUNT(*) as count FROM subscribe LEFT JOIN post ON subscribe.author_id = post.user_id WHERE post.id = ' . $postId . '';
    $result = mysqli_query($connect, $sql);
    return mysqli_fetch_assoc($result);
}

/**
 * Считает количество публикаций для конкретного пользователя по переданному id поста
 * @param mysqli $connect
 * @param int|null $postId - id текущего поста
 * @return array
 */
function countPosts(mysqli $connect, ?int $postId): array
{
    $sqlPost = 'SELECT post.user_id FROM `post` LEFT JOIN `user` ON post.user_id = user.id WHERE post.id = ' . $postId . '';
    $resultPost = mysqli_query($connect, $sqlPost);
    $userId = mysqli_fetch_assoc($resultPost)['user_id'];

    $sql = 'SELECT COUNT(*) as count FROM post LEFT JOIN user ON user.id = post.user_id WHERE post.user_id = ' . $userId . '';
    $result = mysqli_query($connect, $sql);
    return mysqli_fetch_assoc($result);
}

/**
 * Обновляет количество лайков для конкретного поста
 * @param mysqli $connect
 * @param int|null $postId - id текущего поста
 */
function updateLikesCount(mysqli $connect, ?int $postId)
{
    $sql = 'UPDATE post SET likes_count = likes_count + 1 WHERE post.id = ' . $postId . '';
    mysqli_query($connect, $sql);
}

/**
 * Обновляет количество просмотров конкретного поста
 * @param mysqli $connect
 * @param int|null $postId - id текущего поста
 */
function updateViewsCount(mysqli $connect, ?int $postId)
{
    $sql = 'UPDATE post SET count_views = count_views + 1 WHERE post.id = ' . $postId . '';
    mysqli_query($connect, $sql);
}
