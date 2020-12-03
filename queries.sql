INSERT INTO `post` VALUES (1, '2020-08-25 11:04:02', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.', 'Неизвестный Автор', NULL, NULL, NULL, 0, 2, 1, 0, 1);
INSERT INTO `post` VALUES (2, '2019-03-15 10:25:35', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', NULL, NULL, NULL, NULL, 0, 1, 2, 0, 2);
INSERT INTO `post` VALUES (3, '2018-07-02 12:22:50', 'Наконец, обработал фотки!', NULL, NULL, 'rock-medium.jpg', NULL, NULL, 0, 3, 3, 0, 3);
INSERT INTO `post` VALUES (4, '2020-07-30 10:31:58', 'Моя мечта', NULL, NULL, 'coast-medium.jpg', NULL, NULL, 0, 3, 1, 0, 1);
INSERT INTO `post` VALUES (5, '2019-07-15 13:10:10', 'Лучшие курсы', NULL, NULL, NULL, NULL, 'www.htmlacademy.ru', 0, 5, 2, 0, 2);

INSERT INTO `user` VALUES (1, '2020-07-25 18:01:02', 'larisa@mail.com', 'larisa', 1111, 'userpic-larisa-small.jpg');
INSERT INTO `user` VALUES (2, '2019-02-15 12:15:24', 'vladik@mail.com', 'vladik', 2222, 'userpic.jpg');
INSERT INTO `user` VALUES (3, '2018-05-02 09:15:00', 'victor@mail.com', 'victor', 3333, 'userpic-mark.jpg');
INSERT INTO `user` VALUES (4, '2020-07-10 20:38:17', 'misha@mail.com', 'misha', 4444, 'userpic-misha.jpg');

INSERT INTO `comment` VALUES (1, '2019-05-12 13:33:43', 'Классная цитата! Все верно!', 1, 1, 2);
INSERT INTO `comment` VALUES (2, '2020-10-12 18:25:44', 'Согласен, тоже хочу посмотреть.', 2, 2, 3);
INSERT INTO `comment` VALUES (3, '2020-11-06 10:55:40', 'Классные курсы, сам там учился!', 2, 5, 3);
INSERT INTO `comment` VALUES (4, '2020-11-10 04:30:25', 'Скорее бы на море!', 1, 4, 3);
INSERT INTO `comment` VALUES (5, '2020-11-15 12:20:00', 'Тоже учился там, все хорошо!', 2, 5, 4);
INSERT INTO `comment` VALUES (6, '2020-11-16 10:01:30', 'Хочу на море', 1, 4, 4);

SELECT post.content, user.login, content_type.name FROM `post` LEFT JOIN `user` ON post.user_id = user.id LEFT JOIN `content_type` ON post.content_type_id = content_type.id WHERE `content` != 'NULL' order by `count_views`; /*Вывести контент, тип контента и логин создателя*/
SELECT * FROM `post` LEFT JOIN `user` ON post.user_id = user.id WHERE `login` = 'larisa'; /*Вывести посты от польщователя с логином larisa*/
SELECT comment.content, user.login FROM `comment` LEFT JOIN `post` ON comment.post_id = post.id LEFT JOIN `user` ON comment.post_creator_id = user.id WHERE `post_id` = 5; /*Вывести комменты и логины к посту №5*/
INSERT INTO `like` VALUE (1, 1, 2); /*Пользователь 1 поставил лайк первому посту посту */
INSERT INTO `like` VALUE (1, 6, 2); /*Пользователь 6 поставил лайк второму посту */
INSERT INTO `subscribe` VALUE (1, 1, 2); /*На пользователя 1 подписался пользователь 2*/
INSERT INTO `subscribe` VALUE (1, 1, 3); /*На пользователя 1 подписался пользователь 3*/
INSERT INTO `subscribe` VALUE (1, 1, 4); /*На пользователя 1 подписался пользователь 4*/