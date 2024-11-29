/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `place` smallint(5) unsigned DEFAULT NULL,
  `game_id` bigint(20) unsigned DEFAULT NULL,
  `player_id` bigint(20) unsigned DEFAULT NULL,
  `map_id` bigint(20) unsigned DEFAULT NULL,
  `value` double(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `analytics_game_id_foreign` (`game_id`),
  KEY `analytics_key_index` (`key`),
  KEY `analytics_player_id_foreign` (`player_id`),
  CONSTRAINT `analytics_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE SET NULL,
  CONSTRAINT `analytics_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(32) NOT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `championships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `championships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `faceit_id` char(36) NOT NULL,
  `name` varchar(64) NOT NULL,
  `region` tinyint(4) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 2,
  `description` text NOT NULL,
  `started_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `championships_faceit_id_unique` (`faceit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `csrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `csrs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) unsigned NOT NULL,
  `playlist_id` bigint(20) unsigned DEFAULT NULL,
  `queue` tinyint(3) unsigned NOT NULL,
  `input` tinyint(3) unsigned NOT NULL,
  `season` tinyint(3) unsigned DEFAULT NULL,
  `season_key` char(6) DEFAULT NULL,
  `mode` tinyint(4) NOT NULL DEFAULT 1,
  `csr` mediumint(8) unsigned DEFAULT NULL,
  `matches_remaining` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `tier` varchar(16) NOT NULL,
  `tier_start_csr` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `sub_tier` tinyint(3) unsigned NOT NULL,
  `next_tier` varchar(16) NOT NULL,
  `next_sub_tier` tinyint(3) unsigned NOT NULL,
  `next_csr` mediumint(8) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `csrs_player_id_playlist_id_queue_input_mode_season_key_unique` (`player_id`,`playlist_id`,`queue`,`input`,`mode`,`season_key`),
  KEY `csrs_playlist_id_foreign` (`playlist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `game_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_players` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) unsigned NOT NULL,
  `game_team_id` bigint(20) unsigned DEFAULT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  `pre_csr` mediumint(8) unsigned DEFAULT NULL,
  `post_csr` mediumint(8) unsigned DEFAULT NULL,
  `matches_remaining` tinyint(4) DEFAULT NULL,
  `rank` tinyint(3) unsigned NOT NULL,
  `outcome` smallint(5) unsigned NOT NULL,
  `was_at_start` tinyint(1) DEFAULT 1,
  `was_at_end` tinyint(1) DEFAULT 1,
  `was_inprogress_join` tinyint(1) DEFAULT 0,
  `kd` double(8,4) NOT NULL,
  `kda` double(8,4) NOT NULL,
  `score` mediumint(8) DEFAULT NULL,
  `mmr` decimal(7,3) DEFAULT NULL,
  `kills` smallint(5) unsigned NOT NULL,
  `deaths` smallint(5) unsigned NOT NULL,
  `assists` smallint(5) unsigned NOT NULL,
  `betrayals` smallint(5) unsigned NOT NULL,
  `suicides` smallint(5) unsigned NOT NULL,
  `max_spree` smallint(5) unsigned DEFAULT NULL,
  `vehicle_destroys` smallint(5) unsigned NOT NULL,
  `vehicle_hijacks` smallint(5) unsigned NOT NULL,
  `medal_count` smallint(5) unsigned NOT NULL,
  `damage_taken` mediumint(7) unsigned NOT NULL,
  `damage_dealt` mediumint(7) unsigned NOT NULL,
  `shots_fired` smallint(5) unsigned NOT NULL,
  `shots_landed` smallint(5) unsigned NOT NULL,
  `shots_missed` smallint(5) unsigned NOT NULL,
  `accuracy` double(5,2) NOT NULL,
  `rounds_won` smallint(5) unsigned DEFAULT NULL,
  `rounds_lost` smallint(5) unsigned DEFAULT NULL,
  `rounds_tied` smallint(5) unsigned DEFAULT NULL,
  `kills_melee` smallint(5) unsigned DEFAULT NULL,
  `kills_grenade` smallint(5) unsigned DEFAULT NULL,
  `kills_headshot` smallint(5) unsigned DEFAULT NULL,
  `kills_power` smallint(5) unsigned DEFAULT NULL,
  `assists_emp` smallint(5) unsigned DEFAULT NULL,
  `assists_driver` smallint(5) unsigned DEFAULT NULL,
  `assists_callout` smallint(5) unsigned DEFAULT NULL,
  `expected_kills` smallint(5) unsigned DEFAULT NULL,
  `expected_deaths` smallint(5) unsigned DEFAULT NULL,
  `medals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_players_game_id_foreign` (`game_id`),
  KEY `game_players_game_team_id_foreign` (`game_team_id`),
  KEY `game_players_player_id_foreign` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `game_scrim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_scrim` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `scrim_id` bigint(20) unsigned NOT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_scrim_scrim_id_foreign` (`scrim_id`),
  KEY `game_scrim_game_id_foreign` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `game_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `internal_team_id` tinyint(3) unsigned NOT NULL,
  `outcome` tinyint(3) unsigned NOT NULL,
  `rank` tinyint(3) unsigned NOT NULL,
  `score` mediumint(9) NOT NULL,
  `mmr` decimal(7,3) DEFAULT NULL,
  `winning_percent` decimal(5,2) DEFAULT NULL,
  `final_score` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_teams_game_id_internal_team_id_unique` (`game_id`,`internal_team_id`),
  KEY `game_teams_team_id_foreign` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `map_id` bigint(20) unsigned NOT NULL,
  `playlist_id` bigint(20) unsigned DEFAULT NULL,
  `gamevariant_id` bigint(20) unsigned DEFAULT NULL,
  `is_ffa` tinyint(1) NOT NULL,
  `is_lan` tinyint(1) unsigned DEFAULT NULL,
  `experience` tinyint(4) NOT NULL,
  `occurred_at` datetime NOT NULL,
  `duration_seconds` int(11) NOT NULL,
  `season_number` tinyint(3) unsigned DEFAULT NULL,
  `season_version` tinyint(3) unsigned DEFAULT NULL,
  `version` varchar(12) NOT NULL DEFAULT '0.0.3',
  `was_pulled` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_uuid_unique` (`uuid`),
  KEY `games_category_id_foreign` (`category_id`),
  KEY `games_map_id_foreign` (`map_id`),
  KEY `games_playlist_id_foreign` (`playlist_id`),
  KEY `games_gamevariant_id_foreign` (`gamevariant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gamevariants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gamevariants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gamevariants_category_id_foreign` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(64) NOT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `level_id` bigint(20) unsigned DEFAULT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(32) NOT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maps_uuid_unique` (`uuid`),
  KEY `maps_level_id_foreign` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `matchup_game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchup_game` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `matchup_id` bigint(20) unsigned NOT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matchup_game_matchup_id_foreign` (`matchup_id`),
  KEY `matchup_game_game_id_foreign` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `matchup_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchup_player` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `matchup_team_id` bigint(20) unsigned NOT NULL,
  `player_id` bigint(20) unsigned DEFAULT NULL,
  `faceit_id` char(36) NOT NULL,
  `faceit_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matchup_player_matchup_team_id_foreign` (`matchup_team_id`),
  KEY `matchup_player_player_id_foreign` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `matchup_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchup_teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `matchup_id` bigint(20) unsigned NOT NULL,
  `faceit_id` char(36) NOT NULL,
  `name` varchar(64) NOT NULL,
  `points` tinyint(4) unsigned DEFAULT NULL,
  `outcome` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matchup_teams_matchup_id_foreign` (`matchup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `matchups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `faceit_id` varchar(64) NOT NULL,
  `championship_id` bigint(20) unsigned NOT NULL,
  `round` tinyint(4) NOT NULL,
  `group` tinyint(4) NOT NULL,
  `best_of` tinyint(4) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matchups_championship_id_foreign` (`championship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `medal_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medal_analytics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) unsigned NOT NULL,
  `season_id` bigint(20) unsigned DEFAULT NULL,
  `medal_id` int(10) unsigned NOT NULL,
  `mode` tinyint(3) unsigned NOT NULL,
  `value` mediumint(8) unsigned NOT NULL,
  `place` smallint(5) unsigned NOT NULL,
  `total_seconds_played` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `medal_analytics_season_id_foreign` (`season_id`),
  KEY `medal_analytics_medal_id_foreign` (`medal_id`),
  KEY `medal_analytics_place_mode_season_id_index` (`place`,`mode`,`season_id`),
  KEY `medal_analytics_player_id_foreign` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `medals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medals` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `difficulty` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `player_bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_bans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `player_id` bigint(20) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `ends_at` datetime NOT NULL,
  `type` varchar(24) NOT NULL,
  `scope` varchar(24) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `player_bans_key_unique` (`key`),
  KEY `player_bans_player_id_foreign` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rank_id` mediumint(8) unsigned DEFAULT NULL,
  `next_rank_id` mediumint(8) unsigned DEFAULT NULL,
  `xuid` varchar(32) DEFAULT NULL,
  `gamertag` varchar(32) NOT NULL,
  `xp` int(10) unsigned DEFAULT NULL,
  `service_tag` varchar(8) DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `is_bot` tinyint(1) NOT NULL DEFAULT 0,
  `is_cheater` tinyint(1) NOT NULL DEFAULT 0,
  `is_botfarmer` tinyint(1) NOT NULL DEFAULT 0,
  `last_game_id_pulled` bigint(20) unsigned DEFAULT NULL,
  `last_custom_game_id_pulled` bigint(20) unsigned DEFAULT NULL,
  `last_lan_game_id_pulled` bigint(20) unsigned DEFAULT NULL,
  `last_csr_key` varchar(8) DEFAULT NULL,
  `emblem_url` varchar(512) DEFAULT NULL,
  `backdrop_url` varchar(512) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `players_gamertag_unique` (`gamertag`),
  UNIQUE KEY `players_xuid_unique` (`xuid`),
  KEY `players_last_game_id_pulled_foreign` (`last_game_id_pulled`),
  KEY `players_last_custom_game_id_pulled_foreign` (`last_custom_game_id_pulled`),
  KEY `players_last_lan_game_id_pulled_foreign` (`last_lan_game_id_pulled`),
  KEY `players_updated_at_index` (`updated_at`),
  KEY `players_is_cheater_index` (`is_cheater`),
  KEY `players_rank_id_foreign` (`rank_id`),
  KEY `players_next_rank_id_foreign` (`next_rank_id`),
  KEY `players_is_botfarmer_index` (`is_botfarmer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `playlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_ranked` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `queue` tinyint(3) unsigned DEFAULT NULL,
  `input` tinyint(3) unsigned DEFAULT NULL,
  `rotations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rotations`)),
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `playlists_is_ranked_index` (`is_ranked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ranks` (
  `id` mediumint(8) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `subtitle` varchar(32) NOT NULL,
  `grade` tinyint(4) DEFAULT NULL,
  `tier` tinyint(4) DEFAULT NULL,
  `type` varchar(12) NOT NULL,
  `threshold` int(11) NOT NULL,
  `required` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `scrims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scrims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `status_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scrims_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `seasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seasons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(12) NOT NULL,
  `identifier` varchar(24) NOT NULL,
  `csr_key` varchar(24) NOT NULL,
  `season_id` tinyint(4) NOT NULL,
  `season_version` tinyint(4) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seasons_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `service_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) unsigned NOT NULL,
  `mode` tinyint(4) NOT NULL DEFAULT 3,
  `season_number` tinyint(3) unsigned DEFAULT 1,
  `season_key` char(6) DEFAULT NULL,
  `kd` double(8,4) NOT NULL,
  `kda` double(8,4) NOT NULL,
  `total_score` int(10) unsigned NOT NULL,
  `total_matches` int(10) unsigned NOT NULL,
  `matches_won` int(10) unsigned NOT NULL,
  `matches_lost` int(10) unsigned NOT NULL,
  `matches_tied` int(10) unsigned NOT NULL,
  `matches_left` int(10) unsigned NOT NULL,
  `total_seconds_played` bigint(20) NOT NULL,
  `kills` int(10) unsigned NOT NULL,
  `deaths` int(10) unsigned NOT NULL,
  `assists` int(10) unsigned NOT NULL,
  `betrayals` mediumint(8) unsigned NOT NULL,
  `suicides` mediumint(8) unsigned NOT NULL,
  `vehicle_destroys` int(10) unsigned NOT NULL,
  `vehicle_hijacks` int(10) unsigned NOT NULL,
  `medal_count` int(10) unsigned NOT NULL,
  `damage_taken` bigint(20) unsigned NOT NULL,
  `damage_dealt` bigint(20) unsigned NOT NULL,
  `shots_fired` bigint(20) unsigned NOT NULL,
  `shots_landed` bigint(20) unsigned NOT NULL,
  `shots_missed` bigint(20) unsigned NOT NULL,
  `accuracy` double(5,2) NOT NULL,
  `kills_melee` int(10) unsigned NOT NULL,
  `kills_grenade` int(10) unsigned NOT NULL,
  `kills_headshot` int(10) unsigned NOT NULL,
  `kills_power` int(10) unsigned NOT NULL,
  `assists_emp` int(10) unsigned NOT NULL,
  `assists_driver` int(10) unsigned NOT NULL,
  `assists_callout` int(10) unsigned NOT NULL,
  `medals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_records_player_id_mode_season_key_unique` (`player_id`,`mode`,`season_key`),
  KEY `service_records_mode_index` (`mode`),
  KEY `service_records_season_number_index` (`season_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `internal_id` tinyint(4) NOT NULL,
  `name` varchar(32) NOT NULL,
  `emblem_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) unsigned DEFAULT NULL,
  `google_id` varchar(32) NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_player_id_foreign` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2021_11_20_135838_add_players_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2021_11_20_142006_add_metadata_tables',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2021_11_21_175835_add_matches_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2021_11_24_104219_fix_unsigned_nature_of_score',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2021_11_25_111532_add_xuid_to_players',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2021_11_26_111551_add_competitive_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2021_11_26_163918_add_ranked_to_games',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2021_11_26_165929_add_service_record',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2021_11_27_111516_add_is_private_to_players',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2021_11_28_185710_drop_useless_tables',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2021_11_28_234442_add_start_tier_to_csr',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2021_11_29_123143_create_jobs_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2021_11_30_105825_add_unique_to_uuid_games',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2021_11_30_105922_add_unique_to_metadata',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2021_12_11_160822_create_game_teams_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2021_12_11_170733_add_internal_team_id_to_game_players',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2021_12_14_010203_add_missing_fields_to_game',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2021_12_14_111623_add_version_to_game',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2021_12_14_115208_add_was_pulled_bool_to_games',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2021_12_15_113038_add_playlist_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2021_12_15_113623_drop_unused_game_columns',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2021_12_15_115237_add_playlist_id_to_games',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2021_12_15_115906_drop_is_ranked_from_games',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2021_12_15_120331_add_partipation_columns',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2021_12_25_124053_add_medals_to_service_record',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2021_12_25_124732_create_medals_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2021_12_28_103825_add_csr_to_game_players',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2022_01_08_122126_add_last_game_id_to_players',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2022_01_09_115837_add_final_score_to_teams',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2022_01_15_152306_drop_image_url_from_csrs',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2022_01_15_202728_add_medals_to_game_players',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2022_01_15_214941_create_cache_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2022_01_23_200035_add_faceit_championship',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2022_01_24_111258_add_matchups',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2022_02_01_235047_add_mmr_to_gameteam',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2022_02_06_112708_add_game_id_to_players',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2022_02_07_004937_add_multiple_record_support',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2022_02_12_112013_add_type_to_championship',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2022_02_06_164854_create_matchup_games',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2022_02_21_105933_tweak_csr_for_all_modes',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2022_02_21_112301_remove_fk_csrs',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2022_02_21_113039_remake_csr_fks',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2022_03_02_115047_add_csr_key_to_players',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2022_03_05_121859_add_users_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2022_03_05_140233_add_scrim_tables',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2022_03_06_015134_add_remember_token_to_users',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2022_03_09_110315_correct_questionmark_gamertags',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2022_03_22_221818_add_last_lan_game_id_pulled',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2022_03_23_095041_drop_version_from_metadata',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2022_03_23_095607_add_team_model',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2022_03_23_101009_rename_medal_tables',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2022_03_24_102807_expand_length_of_game_version',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2022_03_24_103210_move_team_to_game_team',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2022_03_24_104012_drop_team_info_from_game_team',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2022_03_24_110213_add_is_local_to_game',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2022_03_25_105934_drop_image_from_medals',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2022_04_15_111651_add_mmr_to_gameplayers',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2022_04_19_101328_add_gamevariants_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2022_04_21_104947_add_mmr_to_players',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2022_04_23_173246_add_winning_chance_to_teams',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2022_04_24_151832_add_stddev_for_players',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2022_05_02_101046_add_season_fields_to_games',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2022_05_02_105022_add_season_to_service_record',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2022_05_14_103445_make_season_number_nullable',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2022_06_07_154628_expand_length_of_gamevariant_name',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2022_06_11_104525_add_is_bot_to_players',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2022_06_25_222554_add_analytic_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2022_06_27_232325_add_is_cheater_to_players',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2022_06_29_103356_make_player_id_nullable_on_analytics',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2022_07_02_102829_grow_columns_on_sr',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2022_08_06_114444_add_max_spree',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2022_08_23_223752_add_playlist_id_to_csr',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2022_08_23_225503_remake_csr_fks_part2',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2022_08_25_102520_add_matches_remaining_to_game_players',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2023_02_16_114304_adjust_nullable_for_matchups',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2023_02_17_115610_add_new_fields_to_faceit',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2023_02_17_122109_add_nulls_for_points_on_teams',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2023_02_21_012419_add_index_for_where',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2023_02_25_122500_cleanup_lan_issue',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2023_02_25_124855_indexes_for_service_records',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2023_03_25_171951_drop_thumbnail_from_playlist',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2023_03_26_220946_migrate_maps',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2023_03_26_222711_migrate_categories',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2023_03_28_222943_add_map_id_to_analytics',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2023_04_01_190024_add_is_cheater_index',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2023_04_08_111909_add_levels_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2023_04_08_192431_add_category_id_to_gamevariants',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2023_04_08_202251_add_level_id_to_maps',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2023_04_08_210941_make_category_nullable',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2023_04_08_210941_make_category_nullable',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2023_04_09_114604_drop_is_scored_from_games',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2023_04_09_152451_add_seasons_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2023_04_13_104347_add_season_key_to_service_record',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2023_04_14_100302_add_season_key_to_csrs',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2023_04_14_231405_remove_mmr_on_players',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2023_04_16_224132_add_player_ban_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2023_04_19_104829_make_final_score_signed',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2023_04_29_101818_add_medal_analytics',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2023_04_30_204534_add_mode_place',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2023_05_08_101909_cascade_player_on_analytics',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2023_05_16_110144_add_unknown_level',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2023_05_06_172314_add_rotation_json_to_playlists',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2023_05_06_172821_add_is_active_to_playlists',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2023_05_10_103016_add_more_fields_to_playlists',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2023_06_22_005112_add_career_ranks',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2023_06_22_094554_add_career_rank_to_player',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2023_06_29_101044_add_place_to_analytics',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2023_08_11_095401_add_is_botfarmer_to_players',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2023_08_30_093058_swap_urls_to_text',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2023_10_15_101405_add_index_to_is_ranked',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2023_12_06_000905_adjust_name_column_on_playlist',63);
