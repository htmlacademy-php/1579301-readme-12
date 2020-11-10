CREATE TABLE `user` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `post` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `header` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `quote_author` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `video` varchar(100) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `count_views` int UNSIGNED NOT NULL,
  `content_type_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_content_type_fk_idx` (`content_type_id`),
  KEY `post_user_fk_idx` (`user_id`),
  CONSTRAINT `post_content_type_fk` FOREIGN KEY (`content_type_id`) REFERENCES `content_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `content_type` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class_icon` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `content_type` VALUES (1,'Текст','text'),(2,'Цитата','quote'),(3,'Картинка','photo'),(4,'Видео','video'),(5,'Ссылка','link');

CREATE TABLE `hashtag` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `post_hashtag` (
  `id` int UNSIGNED NOT NULL,
  `post_id` int NOT NULL,
  `hashtag_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_hashtag_post_fk_idx` (`post_id`),
  KEY `post_hashtag_hashtag_fk_idx` (`hashtag_id`),
  CONSTRAINT `post_hashtag_hashtag_fk` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_hashtag_post_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comment` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `content` text NOT NULL,
  `post_creator_id` int NOT NULL,
  `post_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_post_creator_fk_idx` (`post_creator_id`),
  KEY `comment_post_fk_idx` (`post_id`),
  CONSTRAINT `comment_post_creator_fk` FOREIGN KEY (`post_creator_id`) REFERENCES `post` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comment_post_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `like` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `like_user_fk_idx` (`user_id`),
  KEY `like_post_fk_idx` (`post_id`),
  CONSTRAINT `like_post_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `like_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `subscribe` (
  `id` int UNSIGNED NOT NULL,
  `author_id` int NOT NULL,
  `subscriber_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subscribe_author_id_user_fk_idx` (`author_id`),
  KEY `subscribe_subscriber_id_user_fk_idx` (`subscriber_id`),
  CONSTRAINT `subscribe_author_id_user_fk` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subscribe_subscriber_id_user_fk` FOREIGN KEY (`subscriber_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `message` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `content` text NOT NULL,
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_sender_id_user_fk_idx` (`sender_id`),
  KEY `message_recipient_id_user_idx` (`recipient_id`),
  CONSTRAINT `message_sender_id_user_fk` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `message_recipient_id_user_fk` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


