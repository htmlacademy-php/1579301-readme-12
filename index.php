<?php

require_once 'helpers.php';
require_once 'connectdb.php';

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

$user_name = 'Dima'; // укажите здесь ваше имя

$title = 'readme';

$mainContent = include_template('main.php', ['cards' => $cards]);

$layoutContent = include_template('layout.php', ['mainContent' => $mainContent, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name]);

print $layoutContent;
