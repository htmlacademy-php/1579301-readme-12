<?php
$is_auth = rand(0, 1);

$cards = [
    [    'header' => 'Цитата',
         'type' => 'post-quote',
         'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих',
         'userName' => 'Лариса',
         'userPic' => 'userpic-larisa-small.jpg',
    ],
    [    'header' => 'Игра престолов',
         'type' => 'post-text',
         'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
         'userName' => 'Владик',
         'userPic' => 'userpic.jpg',
    ],
    [    'header' => 'Наконец, обработал фотки!',
         'type' => 'post-photo',
         'content' => 'rock-medium.jpg',
         'userName' => 'Виктор',
         'userPic' => 'userpic-mark.jpg',
    ],
    [    'header' => 'Моя мечта',
         'type' => 'post-photo',
         'content' => 'coast-medium.jpg',
         'userName' => 'Лариса',
         'userPic' => 'userpic-larisa-small.jpg',
    ],
    [    'header' => 'Лучшие курсы',
         'type' => 'post-link',
         'content' => 'www.htmlacademy.ru',
         'userName' => 'Владик',
         'userPic' => 'userpic.jpg',
    ],
];

/**
 * Обрезает текст до указанной длины и
 * добавляет в конце знак троеточия
 * @param string $string Строка для обрезания
 * @param integer $length Длина строки
 *
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

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
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

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['cards' => $cards]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
