<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
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
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * Генерирует случайную дату в формате ГГГГ:ММ:ДД Ч:М:С
 * @param $index
 * @return false|string
 */
function generate_random_date(int $index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Обрезает текст до указанной длины и
 * добавляет в конце знак троеточия
 * @param string $string Строка для обрезания
 * @param integer $length Длина строки
 * @return string Обрезанная строка
 */
function cutText(string $string, int $length = 300) : string
{
    $wordsArray = explode(' ', $string);
    $countCharacters = 0;
    $newArray = [];

    foreach ($wordsArray as $word) {
        $countCharacters += mb_strlen($word);

        if ($countCharacters <= $length) {
            $newArray[] = $word;
            $countCharacters += 1; // прибавляем пробел
        } else {
            break;
        }
    }
    return implode(' ', $newArray);
}

define('HOUR', 60);
define('DAY', 1440);
define('WEEK', 10080);
define('MONTH', 43800);
define('FIVE_WEEKS', 50400);
/**
 * Показывает дату в виде количества прошедших с данного
 * момента минут, часов, дней, недель или месяцев.
 * @param string $postTime Дата в виде Y-m-d H:i:s
 * @return string Cтрока с относительной датой
 */
function timePassedAfterPublication(string $postTime) : string
{
    $currentTime = time();

    if ($currentTime > strtotime($postTime)) {

        $relativeTimeSec = $currentTime - strtotime($postTime);
        $relativeTimeMin = $relativeTimeSec / 60;

        if ($relativeTimeMin < HOUR) {
            return $relativeTimeMin . get_noun_plural_form($relativeTimeMin, ' минута', ' минуты', ' минут') . ' назад';
        }
        if (HOUR <= $relativeTimeMin && $relativeTimeMin < DAY) {
            return $relativeTimeMin / HOUR . get_noun_plural_form($relativeTimeMin / HOUR, ' час', ' часа', ' часов') . ' назад';
        }
        if (DAY <= $relativeTimeMin && $relativeTimeMin < WEEK) {
            return $relativeTimeMin / DAY . get_noun_plural_form($relativeTimeMin / DAY, ' день', ' дня', ' дней') . ' назад';
        }
        if (WEEK <= $relativeTimeMin && $relativeTimeMin < FIVE_WEEKS) {
            return floor($relativeTimeMin / WEEK) . get_noun_plural_form($relativeTimeMin / WEEK, ' неделю', ' недели', ' недель') . ' назад';
        }
        return floor($relativeTimeMin / MONTH) . get_noun_plural_form($relativeTimeMin / MONTH, ' месяц', ' месяца', ' месяцев') . ' назад';
    } else {
        return "0 минут назад";
    }
}

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
    $sqlPost = 'SELECT post.id, post.content, post.content_type_id, post.picture, post.link, post.quote_author, post.header, post.create_time, user.login, user.avatar, content_type.class_icon FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id WHERE post.id = ' . $postId . '';
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