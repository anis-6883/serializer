DROP TABLE IF EXISTS apps;

CREATE TABLE `apps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_unique_id` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public/default/app.png',
  `onesignal_app_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `onesignal_api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_publishing_control` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ads_control` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ios_share_link` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ios_app_publishing_control` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ios_ads_control` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `privacy_policy` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telegram` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_countries` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO apps VALUES('15','1662947666_1117793043','Wind','public/uploads/images/apps/APP_1662947727_1572440080.png','12345','12345','on','on','http://localhost/hotserial/apps/create','on','on','','','','','[\"Bangladesh\",\"Pakistan\"]','1','2022-09-12 01:55:10','2022-09-12 01:55:27');
INSERT INTO apps VALUES('16','1662947731_619275949','Bkash','public/uploads/images/apps/APP_1662947777_1548082885.jpg','12345','12345','on','on','http://localhost/hotserial/apps/create','on','on','','','','','[\"Bangladesh\",\"India\",\"Pakistan\"]','1','2022-09-12 01:56:17','2022-09-12 01:56:17');
INSERT INTO apps VALUES('17','1662947781_561200363','MyGP','public/uploads/images/apps/APP_1662947843_1102025526.png','12345','67890','on','on','http://localhost/hotserial/apps/create','on','on','','','','','[\"Andorra\",\"Belgium\",\"China\"]','1','2022-09-12 01:57:23','2022-09-12 01:57:23');



DROP TABLE IF EXISTS episode_apps;

CREATE TABLE `episode_apps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` bigint(20) unsigned NOT NULL,
  `episode_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO episode_apps VALUES('52','16','21','2022-09-12 05:42:21','2022-09-12 05:42:21');
INSERT INTO episode_apps VALUES('53','16','22','2022-09-12 06:07:33','2022-09-12 06:07:33');
INSERT INTO episode_apps VALUES('54','17','22','2022-09-12 06:07:33','2022-09-12 06:07:33');
INSERT INTO episode_apps VALUES('68','15','20','2022-09-12 10:08:40','2022-09-12 10:08:40');
INSERT INTO episode_apps VALUES('69','16','20','2022-09-12 10:08:40','2022-09-12 10:08:40');
INSERT INTO episode_apps VALUES('70','15','23','2022-09-12 10:31:07','2022-09-12 10:31:07');



DROP TABLE IF EXISTS episodes;

CREATE TABLE `episodes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `serial_id` bigint(20) unsigned NOT NULL,
  `episode_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image_type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `episode_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO episodes VALUES('20','1','TTT','url','https://images.unsplash.com/photo-1593673953398-6b9b2d227af1?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Y293Ym95fGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60','','1','2022-09-12 03:57:40','2022-09-12 02:49:08','2022-09-12 10:06:22');
INSERT INTO episodes VALUES('22','1','Episode 2001','url','https://images.unsplash.com/photo-1661961112134-fbce0fdf3d99?ixlib=rb-1.2.1&ixid=MnwxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60','','1','2022-09-12 12:07:15','2022-09-12 06:07:33','2022-09-12 06:07:33');



DROP TABLE IF EXISTS failed_jobs;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS migrations;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO migrations VALUES('1','2014_10_12_000000_create_users_table','1');
INSERT INTO migrations VALUES('2','2014_10_12_100000_create_password_resets_table','1');
INSERT INTO migrations VALUES('3','2019_08_19_000000_create_failed_jobs_table','1');
INSERT INTO migrations VALUES('4','2019_12_14_000001_create_personal_access_tokens_table','1');
INSERT INTO migrations VALUES('5','2022_09_05_115648_create_settings_table','1');
INSERT INTO migrations VALUES('7','2022_09_06_101630_create_apps_table','2');
INSERT INTO migrations VALUES('10','2022_09_08_085344_create_serials_table','3');
INSERT INTO migrations VALUES('14','2022_09_11_004732_create_episodes_table','4');
INSERT INTO migrations VALUES('15','2022_09_11_073113_create_episode_apps_table','5');
INSERT INTO migrations VALUES('16','2022_09_11_073623_create_streaming_sources_table','6');



DROP TABLE IF EXISTS password_resets;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS personal_access_tokens;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS serials;

CREATE TABLE `serials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `serial_unique_id` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public/default/serial.png',
  `serial_order` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO serials VALUES('1','1662639831_425890099','ABC','public/uploads/images/serials/SERIAL_1662639844_392338950.png','3','1','2022-09-08 12:24:04','2022-09-08 12:57:59');
INSERT INTO serials VALUES('2','1662639857_81129430','DEF','public/uploads/images/serials/SERIAL_1662639868_1418772057.png','1','1','2022-09-08 12:24:28','2022-09-08 12:57:59');
INSERT INTO serials VALUES('3','1662640044_1741306539','GHI','public/uploads/images/serials/SERIAL_1662640081_1923014884.png','2','1','2022-09-08 12:28:01','2022-09-08 12:57:59');



DROP TABLE IF EXISTS settings;

CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings VALUES('1','company_name','Root Devs','2022-09-05 16:30:59','2022-09-12 11:13:32');
INSERT INTO settings VALUES('2','site_title','Hot Serial','2022-09-05 16:30:59','2022-09-12 11:13:32');
INSERT INTO settings VALUES('3','timezone','Asia/Dhaka','2022-09-05 16:30:59','2022-09-12 11:13:32');
INSERT INTO settings VALUES('4','language','English','2022-09-05 16:30:59','2022-09-12 11:13:32');
INSERT INTO settings VALUES('5','android_version_code','1','2022-09-05 16:30:59','2022-09-05 16:30:59');
INSERT INTO settings VALUES('6','ios_version_code','1','2022-09-05 16:30:59','2022-09-05 16:30:59');
INSERT INTO settings VALUES('7','android_live_control','off','2022-09-05 16:30:59','2022-09-05 16:30:59');
INSERT INTO settings VALUES('8','ios_live_control','off','2022-09-05 16:30:59','2022-09-05 16:30:59');
INSERT INTO settings VALUES('9','privacy_policy','https://superfootball.com/','2022-09-05 16:30:59','2022-09-05 16:30:59');
INSERT INTO settings VALUES('10','facebook','https://www.facebook.com/','2022-09-05 16:30:59','2022-09-12 11:13:52');
INSERT INTO settings VALUES('11','youtube','http://youtube.com/','2022-09-05 16:30:59','2022-09-12 11:13:52');
INSERT INTO settings VALUES('12','instagram','https://instagram.com/','2022-09-05 16:30:59','2022-09-12 11:13:52');



DROP TABLE IF EXISTS streaming_sources;

CREATE TABLE `streaming_sources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` bigint(20) unsigned NOT NULL,
  `stream_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stream_type` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resulation` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stream_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO streaming_sources VALUES('16','21','Title BTT','m3u8','720p','http://localhost/hotserial/episodes/create','','2022-09-12 05:42:21','2022-09-12 05:42:21');
INSERT INTO streaming_sources VALUES('17','22','Stream 1','restricted','1080p','https://images.unsplash.com/photo-1661961112134-fbce0fdf3d99?ixlib=rb-1.2.1&ixid=MnwxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60','{\"Content-Type\":\"application\\/json; charset=UTF-8\"}','2022-09-12 06:07:33','2022-09-12 06:07:33');
INSERT INTO streaming_sources VALUES('36','20','T1','restricted','720p','http://localhost/hotserial/episodes/create','{\"Content-Type\":\"application\\/json; charset=UTF-8\",\"cyber_key\":\"lfmodr~!+H*2F+0HnSwz%^kXyp!Vs-k#+--)0y;iEYJK<W5H.pe=G?9gBdfN^dh=\"}','2022-09-12 10:08:40','2022-09-12 10:08:40');
INSERT INTO streaming_sources VALUES('37','20','T2','restricted','480p','http://localhost/hotserial/episodes/create','{\"server_key\":\"12345\",\"starlink_key\":\"Anis\"}','2022-09-12 10:08:40','2022-09-12 10:08:40');
INSERT INTO streaming_sources VALUES('38','20','Super Star','restricted','720p','https://images.unsplash.com/photo-1580927752452-89d86da3fa0a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80','{\"Charset\":\"UTF-8\",\"accept\":\"application\\/json\"}','2022-09-12 10:08:40','2022-09-12 10:08:40');
INSERT INTO streaming_sources VALUES('39','20','Monkey','m3u8','1080p','https://images.unsplash.com/flagged/photo-1566127992631-137a642a90f4?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8bW9ua2V5fGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60','','2022-09-12 10:08:40','2022-09-12 10:08:40');



DROP TABLE IF EXISTS users;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default/profile.png',
  `status` int(11) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users VALUES('1','Root','Developers','rootdevs.developers11@gmail.com','admin','','$2y$10$UPuknbWkSqzE0JxSgCCgneAZrjAVqaG0zFq5bISHxteeZHQYSfFkm','default/profile.png','1','jnLZOT4TYmK5glXzVlxJXR7rYHGk4I3nkjXy7d4WGH5vAlbwmJWRnaijj2JH','2022-09-05 16:30:03','2022-09-05 16:30:03');



