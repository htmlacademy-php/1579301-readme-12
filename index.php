<?php

require_once 'helpers.php';

$is_auth = rand(0, 1);

$cards = [
    [    'header' => 'Цитата',
         'type' => 'post-quote',
         'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих',
         'userName' => 'Лариса',
         'userPic' => 'userpic-larisa-small.jpg',
         'publicTime' => generate_random_date(0),
    ],
    [    'header' => 'Игра престолов',
         'type' => 'post-text',
         'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
         'userName' => 'Владик',
         'userPic' => 'userpic.jpg',
         'publicTime' => generate_random_date(1),
    ],
    [    'header' => 'Наконец, обработал фотки!',
         'type' => 'post-photo',
         'content' => 'rock-medium.jpg',
         'userName' => 'Виктор',
         'userPic' => 'userpic-mark.jpg',
        'publicTime' => generate_random_date(2),
    ],
    [    'header' => 'Моя мечта',
         'type' => 'post-photo',
         'content' => 'coast-medium.jpg',
         'userName' => 'Лариса',
         'userPic' => 'userpic-larisa-small.jpg',
         'publicTime' => generate_random_date(3),
    ],
    [    'header' => 'Лучшие курсы',
         'type' => 'post-link',
         'content' => 'www.htmlacademy.ru',
         'userName' => 'Владик',
         'userPic' => 'userpic.jpg',
         'publicTime' => generate_random_date(4),
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

function timePassedAfterPublication($relativeTimeMin)
{
    if ($relativeTimeMin < 60) {
        return $relativeTimeMin . get_noun_plural_form($relativeTimeMin, ' минута', ' минуты', ' минут') . ' назад';
    } elseif (60 <= $relativeTimeMin && $relativeTimeMin < 1440) {
        return $relativeTimeMin / 60 . get_noun_plural_form($relativeTimeMin / 60, ' час', ' часа', ' часов') . ' назад';
    } elseif (1440 <= $relativeTimeMin && $relativeTimeMin < 34560) {
        return $relativeTimeMin / 1440 . get_noun_plural_form($relativeTimeMin / 1440, ' день', ' дня', ' дней') . ' назад';
    } elseif (34560 <= $relativeTimeMin && $relativeTimeMin < 172800) {
        return floor($relativeTimeMin / 34560) . get_noun_plural_form($relativeTimeMin / 34560, ' неделю', ' недели', ' недель') . ' назад';
    } elseif (172800 <= $relativeTimeMin) {
        return floor($relativeTimeMin / 172800) . get_noun_plural_form($relativeTimeMin / 172800, ' месяц', ' месяца', ' месяцев') . ' назад';
    }
}

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['cards' => $cards]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
