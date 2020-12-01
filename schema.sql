CREATE TABLE `user` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_udx` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `content_type` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class_icon` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `content_type` VALUES (1,'Текст','text'),(2,'Цитата','quote'),(3,'Картинка','photo'),(4,'Видео','video'),(5,'Ссылка','link');

CREATE TABLE `post` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `header` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `quote_author` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `video` varchar(100) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `count_views` int UNSIGNED DEFAULT 0,
  `content_type_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `is_repost` tinyint(1) DEFAULT 0,
  `original_user_id` int UNSIGNED NULL,
  PRIMARY KEY (`id`),
  KEY `post_content_type_id_idx` (`content_type_id`),
  KEY `post_user_id_idx` (`user_id`),
  KEY `post_original_user_id` (`original_user_id`),
  CONSTRAINT `post_content_type_id_fk` FOREIGN KEY (`content_type_id`) REFERENCES `content_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_original_user_id_fk` FOREIGN KEY (`original_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `hashtag` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `post_hashtag` (
  `id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `hashtag_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_hashtag_post_id_idx` (`post_id`),
  KEY `post_hashtag_hashtag_id_idx` (`hashtag_id`),
  CONSTRAINT `post_hashtag_hashtag_id_fk` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_hashtag_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comment` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `content` text NOT NULL,
  `post_creator_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_post_creator_id_idx` (`post_creator_id`),
  KEY `comment_post_id_idx` (`post_id`),
  CONSTRAINT `comment_post_creator_id_fk` FOREIGN KEY (`post_creator_id`) REFERENCES `post` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comment_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `like` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `like_user_id_idx` (`user_id`),
  KEY `like_post_id_idx` (`post_id`),
  CONSTRAINT `like_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `like_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `subscribe` (
  `id` int UNSIGNED NOT NULL,
  `author_id` int UNSIGNED NOT NULL,
  `subscriber_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subscribe_author_id_idx` (`author_id`),
  KEY `subscribe_subscriber_id_idx` (`subscriber_id`),
  CONSTRAINT `subscribe_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subscribe_subscriber_id_fk` FOREIGN KEY (`subscriber_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `message` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` datetime NOT NULL,
  `content` text NOT NULL,
  `sender_id` int UNSIGNED NOT NULL,
  `recipient_id` int UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_sender_id_idx` (`sender_id`),
  KEY `message_recipient_id_idx` (`recipient_id`),
  CONSTRAINT `message_sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `message_recipient_id_fk` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


