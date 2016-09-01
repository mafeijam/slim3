CREATE TABLE `share_like` (
 `user_id` int(11) NOT NULL,
 `share_id` int(11) NOT NULL,
 UNIQUE KEY `like` (`user_id`,`share_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci