CREATE TABLE `shares` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `cat_id` int(11) NOT NULL,
 `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `body` text COLLATE utf8_unicode_ci NOT NULL,
 `views` int(10) unsigned NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci