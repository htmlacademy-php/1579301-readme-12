INSERT INTO `content_type` VALUES (1,'Текст','post-text', 20, 21),(2,'Цитата','post-quote', 21, 20),(3,'Картинка','post-photo', 22, 18),(4,'Видео','post-video', 22, 16),(5,'Ссылка','post-link', 21, 18);

INSERT INTO `user` VALUES (1, '2020-07-25 18:01:02', 'larisa@mail.com', 'larisa', 1111, 'userpic-larisa-small.jpg');
INSERT INTO `user` VALUES (2, '2019-02-15 12:15:24', 'vladik@mail.com', 'vladik', 2222, 'userpic.jpg');
INSERT INTO `user` VALUES (3, '2018-05-02 09:15:00', 'victor@mail.com', 'victor', 3333, 'userpic-mark.jpg');
INSERT INTO `user` VALUES (4, '2020-07-10 20:38:17', 'misha@mail.com', 'misha', 4444, 'userpic-misha.jpg');

INSERT INTO `post` VALUES (1, '2020-08-25 11:04:02', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.', 'Неизвестный Автор', NULL, NULL, NULL, NULL, 0, 2, 1, 0, 1, 0, 0);
INSERT INTO `post` VALUES (2, '2019-03-15 10:25:35', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', NULL, NULL, NULL, NULL ,NULL, 0, 1, 2, 0, 2, 0, 0);
INSERT INTO `post` VALUES (3, '2018-07-02 12:22:50', 'Наконец, обработал фотки!', NULL, NULL, 'rock-medium.jpg', NULL, NULL, NULL, 0, 3, 3, 0, 3, 0, 0);
INSERT INTO `post` VALUES (4, '2020-07-30 10:31:58', 'Моя мечта', NULL, NULL, 'coast-medium.jpg', NULL, NULL, NULL, 0, 3, 1, 0, 1, 0, 0);
INSERT INTO `post` VALUES (5, '2019-07-15 13:10:10', 'Лучшие курсы', NULL, NULL, NULL, NULL, NULL, 'https://htmlacademy.ru', 0, 5, 2, 0, 2, 0, 0);
INSERT INTO `post` VALUES (6, '2021-01-20 12:15:22', 'Полезный пост про Байкал', 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках. Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.', NULL, NULL, NULL, NULL, NULL, 0, 2, 1, 0, 1, 0, 0);
INSERT INTO `post` VALUES (7, '2021-01-01 13:22:10', 'Цитата дня', 'Тысячи людей живут без любви, но никто — без воды.', 'Хью Оден', NULL, NULL, NULL, NULL, 0, 2, 1, 0, 1, 0, 0);
INSERT INTO `post` VALUES (8, '2020-12-31 10:25:18', 'Делюсь с вами ссылочкой', NULL, NULL, NULL, NULL, NULL, 'https://vitadental.ru', 0, 5, 2, 0, 2, 0, 0);

INSERT INTO `comment` VALUES (1, '2019-05-12 13:33:43', 'Классная цитата! Все верно!', 1, 1, 2);
INSERT INTO `comment` VALUES (2, '2020-10-12 18:25:44', 'Согласен, тоже хочу посмотреть.', 2, 2, 3);
INSERT INTO `comment` VALUES (3, '2020-11-06 10:55:40', 'Классные курсы, сам там учился!', 2, 5, 3);
INSERT INTO `comment` VALUES (4, '2020-11-10 04:30:25', 'Скорее бы на море!', 1, 4, 3);
INSERT INTO `comment` VALUES (5, '2020-11-15 12:20:00', 'Тоже учился там, все хорошо!', 2, 5, 4);
INSERT INTO `comment` VALUES (6, '2020-11-16 10:01:30', 'Хочу на море', 1, 4, 4);
INSERT INTO `comment` VALUES (7, '2021-01-16 09:55:12', 'Цитата что надо!', 1, 1, 3);
INSERT INTO `comment` VALUES (8, '2021-01-22 12:14:00', 'Да, нормально!', 1, 1, 4);

SELECT post.content, user.login, content_type.name FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id order by `count_views`; /*Вывести контент, тип контента и логин создателя*/
SELECT * FROM `post` LEFT JOIN `user` ON post.user_id = user.id WHERE user.id = 1; /*Вывести посты от польщователя с id = 1*/
SELECT comment.content, user.login FROM `comment` LEFT JOIN `user` ON comment.post_creator_id = user.id WHERE `post_id` = 5; /*Вывести комменты и логины к посту №5*/

INSERT INTO `like` VALUE (1, 1, 2); /*Пользователь 1 поставил лайк первому посту посту */
INSERT INTO `like` VALUE (2, 4, 2); /*Пользователь 4 поставил лайк второму посту */
INSERT INTO `subscribe` VALUE (1, 1, 2); /*На пользователя 1 подписался пользователь 2*/
INSERT INTO `subscribe` VALUE (2, 1, 3); /*На пользователя 1 подписался пользователь 3*/
INSERT INTO `subscribe` VALUE (3, 1, 4); /*На пользователя 1 подписался пользователь 4*/
INSERT INTO `subscribe` VALUE (4, 2, 1); /*На пользователя 2 подписался пользователь 1*/
INSERT INTO `subscribe` VALUE (5, 2, 3); /*На пользователя 2 подписался пользователь 3*/