<?php

/**
 * Подключает к базе данных
 * @param array $dbParams
 * @return mysqli
 */
function dbConnect(array $dbParams) : mysqli
{
    $connect = mysqli_connect($dbParams['host'], $dbParams['user'], $dbParams['password'], $dbParams['database']);

    if (!$connect) {
        exit("Ошибка подключения: " . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");
    return $connect;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает посты пользователя
 * @param mysqli $connect
 * @param array|null $criteria
 * @return array
 */
function getPosts(mysqli $connect, ?array $criteria) : array
{
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.header, post.create_time, post.comments_count, post.likes_count, user.login, user.avatar, content_type.class_icon 
                FROM `post` LEFT JOIN `user` ON post.user_id = user.id 
                LEFT JOIN `content_type` ON post.content_type_id = content_type.id';
    if ($criteria['contentTypeId']) {
        $sqlPost .= ' WHERE `content_type_id` = ' . $criteria['contentTypeId'] . '';
    }

    $sort = 'count_views';

    if (isset($criteria['sort']) && $criteria['sort']['type'] == 'popularity') {
        $sort = 'count_views';
    } elseif (isset($criteria['sort']) && $criteria['sort']['type'] == 'like') {
        $sort = 'likes_count';
    } elseif (isset($criteria['sort']) && $criteria['sort']['type'] == 'date') {
        $sort = 'create_time';
    }

    $sqlPost .= ' ORDER BY ' .$sort . ' ' . $criteria['sort']['order'] . ' LIMIT ' . $criteria['pagination']['startItem'] . ', 6';

    $resultPost = mysqli_query($connect, $sqlPost);

    if ($resultPost) {
        return mysqli_fetch_all($resultPost, MYSQLI_ASSOC) ?? [];
    }
        echo "Ошибка" . mysqli_error($connect);
        exit();
}

/**
 * Считает количество постов, учитывая их тип
 * @param mysqli $connect
 * @param string|null $type
 * @return int
 */
function getCountPosts(mysqli $connect, ?string $type): int
{
    $sql = 'SELECT COUNT(*) as count FROM `post`';

    if ($type) {
        $sql .=  'WHERE post.content_type_id = '. $type . '';
    }
    $result = mysqli_query($connect, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'] ?? [];
    }
    echo "Ошибка" . mysqli_error($connect);
    exit();
}

/**
 * Возвращает конкретный пост пользователя по заданному id
 * @param mysqli $connect
 * @param int|null $postId
 * @return array
 */
function getPost(mysqli $connect, int $postId): array
{
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.quote_author, post.header, post.create_time, post.user_id, post.comments_count, post.likes_count, post.count_views, user.login, user.create_time as createUser ,user.avatar, content_type.class_icon FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id WHERE post.id = ' . $postId . '';
    $resultPost = mysqli_query($connect, $sqlPost);

    if ($resultPost) {
        return mysqli_fetch_assoc($resultPost) ?? [];
    } else {
        echo "Ошибка" . mysqli_error($connect);
        exit();
    }
}

/**
 * Возвращает тип контента
 * @param mysqli $connect
 * @return array
 */
function getContentTypes(mysqli $connect) : array
{
    $sqlPost = 'SELECT `id`, `class_icon`, `width_icon`, `height_icon` FROM `content_type`';
    $resultPost = mysqli_query($connect, $sqlPost);
    return mysqli_fetch_all($resultPost, MYSQLI_ASSOC);
}

/**
 * Возвращает комментарии по конкретному посту
 * @param mysqli $connect
 * @param int $postId - id текущего поста
 * @return array
 */
function getCommentData(mysqli $connect, int $postId): array
{
    $sqlPost = 'SELECT comment.create_time, comment.content, user.login, user.avatar FROM `comment` LEFT JOIN `user` ON comment.author_id = user.id WHERE comment.post_id = ' . $postId . ' ORDER BY comment.create_time DESC';
    $resultPost = mysqli_query($connect, $sqlPost);
    return mysqli_fetch_all($resultPost, MYSQLI_ASSOC) ?? [];
}

/**
 * Добавляет комментарий для поста
 * @param mysqli $connect
 * @param int $postId - id текущего поста
 * @param int $postCreatorId - id создателя поста
 * @param array $data - суперглобальный массив $_POST
 * @return bool
 */
function addComment($connect, int $postId, int $postCreatorId, array $data)
{
    date_default_timezone_set( 'Europe/Moscow' );
    $date = date("Y-m-d H:i:s");

    $msg = trim($data['msg']);

    $sql = "INSERT INTO comment (create_time, content, post_creator_id, post_id, author_id) VALUES ('$date', ?, '$postCreatorId', '$postId', 1)";

    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 's', $msg);
    $result = mysqli_stmt_execute($stmt);

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
    $sql = "UPDATE post SET comments_count = comments_count + 1 WHERE post.id = '$postId'";
    mysqli_query($connect, $sql);
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

function isPostIsset(mysqli $connect, ?int $postId)
{
    $sql = 'SELECT post.id FROM `post` WHERE post.id = ' . $postId . '';
    $result = mysqli_query($connect, $sql);
    $post = mysqli_fetch_assoc($result);

    if ($post) {
        return true;
    } else {
        echo 'Пост не найден!';
        exit();
    }
}

