<?php

/**
 * Подключает к базе данных
 * @param array $dbParams
 * @return mysqli
 */
function dbConnect(array $dbParams) : mysqli
{
    $connection = mysqli_connect($dbParams['host'], $dbParams['user'], $dbParams['password'], $dbParams['database']);

    if (!$connection) {
        exit("Ошибка подключения: " . mysqli_connect_error());
    }
    mysqli_set_charset($connection, "utf8");
    return $connection;
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
function db_get_prepare_stmt(mysqli $link, string $sql, array $data = [])
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
 * @param mysqli $connection
 * @param array|null $criteria
 * @return array
 */
function getPosts(mysqli $connection, ?array $criteria) : array
{
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.header, post.create_time, post.comments_count, post.likes_count, post.quote_author, post.video, post.video_cover, user.login, user.avatar, content_type.class_icon 
                FROM `post` LEFT JOIN `user` ON post.user_id = user.id 
                LEFT JOIN `content_type` ON post.content_type_id = content_type.id';
    if ($criteria['contentTypeId']) {
        $sqlPost .= ' WHERE `content_type_id` = ' . $criteria['contentTypeId'] . '';
    }

    $sort = 'count_views';

    if (isset($criteria['sort'])) {
        switch ($criteria['sort']['type']) {
            case 'popularity':
                $sort = 'count_views';
                break;
            case 'like':
                $sort = 'likes_count';
                break;
            case 'date':
                $sort = 'create_time';
                break;
        }
    }

    $sqlPost .= ' ORDER BY ' .$sort . ' ' . $criteria['sort']['order'] . ' LIMIT ' . $criteria['pagination']['startItem'] . ', ' . $criteria['pagination']['postsOnPage'] .'';

    $resultPost = mysqli_query($connection, $sqlPost);

    if ($resultPost) {
        return mysqli_fetch_all($resultPost, MYSQLI_ASSOC) ?? [];
    }
        echo "Ошибка" . mysqli_error($connection);
        exit();
}

/**
 * Считает количество постов, учитывая их тип
 * @param mysqli $connection
 * @param string|null $type
 * @return int
 */
function getCountPosts(mysqli $connection, ?string $type): int
{
    $sql = 'SELECT COUNT(*) as count FROM `post`';

    if ($type) {
        $sql .=  'WHERE post.content_type_id = '. $type . '';
    }
    $result = mysqli_query($connection, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }
    echo "Ошибка" . mysqli_error($connection);
    exit();
}

/**
 * Возвращает конкретный пост пользователя по заданному id
 * @param mysqli $connection
 * @param int|null $postId
 * @return array
 */
function getPost(mysqli $connection, int $postId): array
{
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.quote_author, post.header, post.create_time, post.user_id, post.comments_count, post.likes_count, post.count_views, post.video, post.video_cover, user.login, user.create_time as createUser ,user.avatar, content_type.class_icon FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id WHERE post.id = ' . $postId . '';
    $resultPost = mysqli_query($connection, $sqlPost);

    if ($resultPost) {
        return mysqli_fetch_assoc($resultPost) ?? [];
    } else {
        echo "Ошибка" . mysqli_error($connection);
        exit();
    }
}

/**
 * Возвращает тип контента
 * @param mysqli $connection
 * @return array
 */
function getContentTypes(mysqli $connection) : array
{
    $sqlPost = 'SELECT `id`, `class_icon`, `width_icon`, `height_icon` FROM `content_type`';
    $resultPost = mysqli_query($connection, $sqlPost);
    if ($resultPost) {
        return mysqli_fetch_all($resultPost, MYSQLI_ASSOC);
    } else {
        echo 'Ошибка' . mysqli_error($connection);
        exit();
    }
}

/**
 * Возвращает комментарии по конкретному посту
 * @param mysqli $connection
 * @param int $postId - id текущего поста
 * @return array
 */
function getComments(mysqli $connection, int $postId): array
{
    $sqlPost = 'SELECT comment.create_time, comment.content, user.login, user.avatar FROM `comment` LEFT JOIN `user` ON comment.author_id = user.id WHERE comment.post_id = ' . $postId . ' ORDER BY comment.create_time DESC';
    $resultPost = mysqli_query($connection, $sqlPost);
    if ($resultPost) {
        return mysqli_fetch_all($resultPost, MYSQLI_ASSOC) ?? [];
    } else {
        echo 'Ошибка' . mysqli_error($connection);
    }
}

/**
 * Добавляет комментарий для поста
 * @param mysqli $connection
 * @param int $postId - id текущего поста
 * @param int $postCreatorId - id создателя поста
 * @param array $data - суперглобальный массив $_POST
 * @return bool
 */
function addComment($connection, int $postId, int $postCreatorId, array $data)
{
    date_default_timezone_set( 'Europe/Moscow' );
    $date = date("Y-m-d H:i:s");

    $msg = trim($data['msg']);

    $sql = "INSERT INTO comment (create_time, content, post_creator_id, post_id, author_id) VALUES ('$date', ?, '$postCreatorId', '$postId', 1)";

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 's', $msg);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        echo 'ошибка';
    } else {
        updateCommentsCount($connection, $postId);
    }
}

/**
 * Обновляет общее количество комментариев для поста после каждого нового комментария
 * @param mysqli $connection
 * @param int $postId
 */
function updateCommentsCount($connection, int $postId)
{
    $sql = "UPDATE post SET comments_count = comments_count + 1 WHERE post.id = '$postId'";
    $query = mysqli_query($connection, $sql);

    if (!$query) {
        echo 'Ошибка' . mysqli_error($connection);
        exit();
    }
}

/**
 * Считает количество подписчиков для конкретного пользователя по переданному id поста
 * @param mysqli $connection
 * @param int|null $postId - id текущего поста
 * @return array
 */
function countSubscribers(mysqli $connection, ?int $postId): array
{
    $sql = 'SELECT COUNT(*) as count FROM subscribe LEFT JOIN post ON subscribe.author_id = post.user_id WHERE post.id = ' . $postId . '';
    $result = mysqli_query($connection, $sql);
    return mysqli_fetch_assoc($result);
}

/**
 * Считает количество публикаций для конкретного пользователя по переданному id поста
 * @param mysqli $connection
 * @param int|null $postId - id текущего поста
 * @return array
 */
function countPosts(mysqli $connection, ?int $postId): array
{
    $sqlPost = 'SELECT post.user_id FROM `post` LEFT JOIN `user` ON post.user_id = user.id WHERE post.id = ' . $postId . '';
    $resultPost = mysqli_query($connection, $sqlPost);
    $userId = mysqli_fetch_assoc($resultPost)['user_id'];

    $sql = 'SELECT COUNT(*) as count FROM post LEFT JOIN user ON user.id = post.user_id WHERE post.user_id = ' . $userId . '';
    $result = mysqli_query($connection, $sql);
    return mysqli_fetch_assoc($result);
}

/**
 * Обновляет количество лайков для конкретного поста
 * @param mysqli $connection
 * @param int|null $postId - id текущего поста
 */
function updateLikesCount(mysqli $connection, ?int $postId)
{
    $sql = 'UPDATE post SET likes_count = likes_count + 1 WHERE post.id = ' . $postId . '';
    $query = mysqli_query($connection, $sql);

    if (!$query) {
        echo 'Ошибка' . mysqli_error($connection);
    }
}

/**
 * Обновляет количество просмотров конкретного поста
 * @param mysqli $connection
 * @param int|null $postId - id текущего поста
 */
function updateViewsCount(mysqli $connection, ?int $postId)
{
    $sql = 'UPDATE post SET count_views = count_views + 1 WHERE post.id = ' . $postId . '';
    $query = mysqli_query($connection, $sql);

    if (!$query) {
        echo 'Ошибка' . mysqli_error($connection);
    }
}

/**
 * Проверяет существует ли запрошенный пост
 * @param mysqli $connection
 * @param int|null $postId - id текущего поста
 * @return bool
 */
function isPostIsset(mysqli $connection, ?int $postId)
{
    $sql = 'SELECT post.id FROM `post` WHERE post.id = ' . $postId . '';
    $result = mysqli_query($connection, $sql);
    $post = mysqli_fetch_assoc($result);

    if ($post) {
        return true;
    } else {
        echo 'Пост не найден!';
        exit();
    }
}

/**
 * Добавляет созданный пользователем пост
 * @param mysqli $connection
 * @param $criteria - массив с данными для заполнения таблицы post

 */
function addPost(mysqli $connection, array $data)
{
    $sql = "INSERT INTO `post` VALUES (NULL, now(), ?, ?, ?, ?, ?, ?, ?, 0, ?, 2, 0, 2, 0, 0)";

    $contentTypeId = getIdFromParams($_GET) ?? 1;

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssssi', $data['header'], $data['content'], $data['quote-author'], $data['photo']['name'], $data['video'], $data['videoCover'], $data['link'], $contentTypeId);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        echo 'Ошибка' . mysqli_error($connection);
    }
}

/**
 * Добавляет хештег к позданному посту
 * @param mysqli $connection
 * @param array $hashtag
 * @param $lastPostId
 */
function addHashtag(mysqli $connection, array $hashtag, int $lastPostId)
{
    foreach ($hashtag as $tag) {
        $sql = "INSERT INTO `hashtag` VALUES(NULL, '{$tag}')";
        $result = mysqli_query($connection, $sql);

        if (!$result) {
            echo 'Ошибка' . mysqli_error($connection);
        }

        $lastHashId = mysqli_insert_id($connection);

        $sql = "INSERT INTO `post_hashtag` VALUES(NULL, '{$lastPostId}', '{$lastHashId}')";
        $result = mysqli_query($connection, $sql);

        if (!$result) {
            echo 'Ошибка' . mysqli_error($connection);
        }
    }
}


