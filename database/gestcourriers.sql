-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : jeu. 11 déc. 2025 à 17:07
-- Version du serveur : 11.4.9-MariaDB
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestcourriers`
--

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

DROP TABLE IF EXISTS `absences`;
CREATE TABLE IF NOT EXISTS `absences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `type_absence_id` bigint(20) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `approuvee` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absences_agent_id_foreign` (`agent_id`),
  KEY `absences_type_absence_id_foreign` (`type_absence_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

DROP TABLE IF EXISTS `affectations`;
CREATE TABLE IF NOT EXISTS `affectations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `courrier_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `statut` varchar(191) NOT NULL DEFAULT 'pending',
  `commentaires` text DEFAULT NULL,
  `date_affectation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_traitement` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affectations_courrier_id_foreign` (`courrier_id`),
  KEY `affectations_user_id_foreign` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `affectations`
--

INSERT INTO `affectations` (`id`, `courrier_id`, `agent_id`, `statut`, `commentaires`, `date_affectation`, `date_traitement`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Affecté', 'jklklkmlkl', '2025-12-03 16:36:36', NULL, '2025-12-03 16:36:36', '2025-12-03 16:36:36'),
(2, 3, 2, 'Affecté', 'klllmlm', '2025-12-03 16:38:28', NULL, '2025-12-03 16:38:28', '2025-12-03 16:38:28'),
(3, 6, 1, 'Affecté', NULL, '2025-12-04 09:24:03', NULL, '2025-12-04 09:24:03', '2025-12-04 09:24:03'),
(4, 5, 1, 'Affecté', NULL, '2025-12-04 09:25:02', NULL, '2025-12-04 09:25:02', '2025-12-04 09:25:02'),
(5, 12, 2, 'in_progress', '2jours', '2025-12-11 09:44:17', '2025-12-11 09:44:17', '2025-12-11 09:44:17', '2025-12-11 09:44:17'),
(6, 5, 2, 'pending', NULL, '2025-12-11 09:57:20', '2025-12-11 09:57:20', '2025-12-11 09:57:20', '2025-12-11 09:57:20'),
(7, 12, 3, 'in_progress', NULL, '2025-12-11 09:57:43', '2025-12-11 09:57:43', '2025-12-11 09:57:43', '2025-12-11 09:57:43'),
(8, 7, 2, 'in_progress', 'bonjour', '2025-12-11 10:18:08', '2025-12-11 10:18:08', '2025-12-11 10:18:08', '2025-12-11 10:18:08');

-- --------------------------------------------------------

--
-- Structure de la table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_professionnel` varchar(191) DEFAULT NULL,
  `matricule` varchar(191) NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `status` enum('Agent','Chef de service','Sous-directeur','Directeur','Conseiller Technique','Conseiller Spécial') NOT NULL DEFAULT 'Agent',
  `sexe` enum('Male','Female') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `Place of birth` varchar(191) DEFAULT NULL,
  `photo` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `Emploi` varchar(191) DEFAULT NULL,
  `Grade` varchar(191) DEFAULT NULL,
  `Date_Prise_de_service` date DEFAULT NULL,
  `Personne_a_prevenir` varchar(191) DEFAULT NULL,
  `Contact_personne_a_prevenir` varchar(191) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agents_matricule_unique` (`matricule`),
  UNIQUE KEY `agents_email_unique` (`email`),
  UNIQUE KEY `agents_email_professionnel_unique` (`email_professionnel`),
  KEY `agents_service_id_foreign` (`service_id`),
  KEY `agents_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `email_professionnel`, `matricule`, `first_name`, `last_name`, `status`, `sexe`, `date_of_birth`, `Place of birth`, `photo`, `email`, `phone_number`, `address`, `Emploi`, `Grade`, `Date_Prise_de_service`, `Personne_a_prevenir`, `Contact_personne_a_prevenir`, `service_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '287688C', 'Sié Yacouba', 'COULIBALY', 'Agent', NULL, NULL, NULL, NULL, NULL, '0707584396', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-12-04 13:36:01', '2025-12-04 13:36:01'),
(2, NULL, '410702H', 'Nafata', 'KONE', 'Agent', NULL, NULL, NULL, NULL, NULL, '0707188674', 'Grand Bassam mockeyville', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-12-09 15:06:21', '2025-12-09 15:06:21'),
(3, NULL, '421263X', 'SIAKOURI', 'Justine', 'Agent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, '2025-12-09 15:58:42', '2025-12-09 15:58:42');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contacts_email_unique` (`email`) USING HASH
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `courriers`
--

DROP TABLE IF EXISTS `courriers`;
CREATE TABLE IF NOT EXISTS `courriers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) DEFAULT NULL,
  `type` enum('Incoming','Outgoing','Information','Other_possible_value') DEFAULT NULL,
  `objet` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_courrier` date DEFAULT curdate(),
  `expediteur_nom` varchar(255) NOT NULL DEFAULT 'non spécifié',
  `expediteur_contact` varchar(255) DEFAULT NULL,
  `destinataire_nom` varchar(255) NOT NULL DEFAULT 'Valeur par défaut',
  `destinataire_contact` varchar(255) DEFAULT NULL,
  `statut` enum('reçu','en_traitement','traité','archivé','envoyé') NOT NULL DEFAULT 'reçu',
  `assigne_a` varchar(255) DEFAULT NULL,
  `chemin_fichier` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courriers_reference_unique` (`reference`) USING HASH,
  KEY `courriers_assigne_a_foreign` (`assigne_a`(250))
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `courriers`
--

INSERT INTO `courriers` (`id`, `reference`, `type`, `objet`, `description`, `date_courrier`, `expediteur_nom`, `expediteur_contact`, `destinataire_nom`, `destinataire_contact`, `statut`, `assigne_a`, `chemin_fichier`, `created_at`, `updated_at`) VALUES
(4, '2134', 'Outgoing', 'Information', NULL, '2025-12-04', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-04 07:57:30', '2025-12-04 17:05:58'),
(2, '0210', 'Outgoing', 'info', NULL, '2025-12-01', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-01 15:55:11', '2025-12-01 15:55:11'),
(3, '1010', 'Outgoing', 'Information', NULL, '2025-12-03', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-03 16:37:56', '2025-12-03 16:37:56'),
(6, '203', 'Incoming', 'Information', NULL, '2025-12-04', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-04 07:58:40', '2025-12-04 07:58:40'),
(7, '10425', 'Incoming', 'travail a faire', NULL, '2025-12-05', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-05 08:24:02', '2025-12-05 08:24:02'),
(8, '275', 'Incoming', 'travail a faire', NULL, '2025-12-08', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-08 11:09:26', '2025-12-08 11:09:26'),
(9, '21', 'Incoming', 'TAF', NULL, '2025-12-10', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-10 14:31:11', '2025-12-10 14:31:11'),
(10, '20', 'Outgoing', 'TAF', NULL, '2025-12-10', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-10 14:41:14', '2025-12-10 14:41:14'),
(11, '77', 'Outgoing', 'Avoir', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 09:07:18', '2025-12-11 09:07:18'),
(12, '10', 'Outgoing', 'travail a faire', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 09:33:41', '2025-12-11 09:33:41'),
(13, '281', 'Incoming', 'impots', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 10:37:07', '2025-12-11 10:37:07'),
(14, '11121', 'Outgoing', 'TAF', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 12:21:08', '2025-12-11 12:21:08'),
(15, '001', 'Outgoing', 'TAF', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 12:21:38', '2025-12-11 12:21:38'),
(16, '1111111011002025', 'Incoming', 'TAF', 'tpo', '2025-12-11', 'SAPH', '0707584396', 'S/D GUDEF', '1223566', 'reçu', 'COUL', NULL, '2025-12-11 14:01:42', '2025-12-11 14:01:42'),
(17, '123588996', 'Incoming', 'bnh', 'hgfiuluoioiu', '2025-12-11', 'h,njkll', '014535536', 'bkjlkml', '254966', 'reçu', NULL, NULL, '2025-12-11 16:13:59', '2025-12-11 16:13:59');

-- --------------------------------------------------------

--
-- Structure de la table `directions`
--

DROP TABLE IF EXISTS `directions`;
CREATE TABLE IF NOT EXISTS `directions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `directions_code_unique` (`code`),
  KEY `directions_head_id_foreign` (`head_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `directions`
--

INSERT INTO `directions` (`id`, `name`, `code`, `description`, `head_id`, `created_at`, `updated_at`) VALUES
(1, 'Cabinet Directeur', 'Direction', 'Cabinet du Directeur de la DSESF', NULL, '2025-12-04 09:31:33', '2025-12-04 09:31:33'),
(2, 'Sous-Direction de la Planification et de la Stratégie', 'SDPS', 'Planifie la politique fiscale et évalue les directions pourvoyeuses de recette', NULL, '2025-12-04 09:33:26', '2025-12-04 09:33:26'),
(3, 'Sous_Direction des Etudes et des Evaluations Fiscales', 'SDEEF', 'Etudes et evaluations Fiscales', NULL, '2025-12-04 09:34:47', '2025-12-04 09:34:47'),
(4, 'Sous-Direction de la Prévision et des Statistiques', 'SDPSF', 'Prévisions et Statistiques Fiscales', NULL, '2025-12-04 09:36:46', '2025-12-04 09:36:46'),
(5, 'GUDEF', 'GUDEF', 'Guichet Unique de Dépôt des Etats Financiers', NULL, '2025-12-04 09:37:28', '2025-12-04 09:37:28');

-- --------------------------------------------------------

--
-- Structure de la table `expediteurs`
--

DROP TABLE IF EXISTS `expediteurs`;
CREATE TABLE IF NOT EXISTS `expediteurs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_17_151426_create_courriers_table', 1),
(2, '2025_11_17_155230_create_expediteurs_table', 2),
(3, '2025_11_17_155518_create_type_courriers_table', 3),
(4, '2025_11_17_155900_create_contacts_table', 4),
(5, '2025_11_17_160005_create_affectations_table', 5),
(6, '2025_12_01_000531_create_affectations_table', 6),
(7, '2025_12_03_075449_create_agents_table', 7),
(8, '2025_12_03_075434_create_services_table', 8),
(9, '2025_12_03_075419_create_directions_table', 9),
(10, '2025_12_03_144514_add_profile_fields_to_users_table', 10),
(11, '2025_12_04_121917_make_lieu_de_naissance_nullable_in_agents_table', 11),
(12, '2025_12_04_132311_update_photo_column_in_agents_table', 12),
(13, '2025_12_04_132817_update_email_column_in_agents_table', 13),
(14, '2025_12_04_133355_update__date__prise_de__service_column_in_agents_table', 14),
(15, '2025_12_04_143140_create_presences_table', 15),
(16, '2025_12_04_143949_create_absences_table', 16),
(17, '2025_12_04_145524_create_type_absences_table', 17),
(18, '2025_12_05_123929_rename_user_id_to_agent_id_in_affectations_table', 18),
(19, '2025_12_09_150923_add_email_professionnel_to_agents_table', 19),
(20, '2025_12_10_144650_create_notifications_taches_table', 20),
(21, '2025_12_11_092142_change_statut_column_type_to_enum', 21);

-- --------------------------------------------------------

--
-- Structure de la table `notifications_taches`
--

DROP TABLE IF EXISTS `notifications_taches`;
CREATE TABLE IF NOT EXISTS `notifications_taches` (
  `id_notification` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_agent` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_echeance` timestamp NULL DEFAULT NULL,
  `suivi_par` varchar(100) NOT NULL,
  `priorite` enum('Faible','Moyenne','Élevée','Urgent') NOT NULL DEFAULT 'Moyenne',
  `statut` enum('Non lu','En cours','Complétée','Annulée') NOT NULL DEFAULT 'Non lu',
  `lien_action` varchar(512) DEFAULT NULL,
  `date_lecture` timestamp NULL DEFAULT NULL,
  `date_completion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_notification`),
  KEY `notifications_taches_id_agent_foreign` (`id_agent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `heure_arrivee` timestamp NOT NULL,
  `heure_depart` timestamp NULL DEFAULT NULL,
  `statut` enum('Absent','Présent','En Retard') NOT NULL DEFAULT 'Présent',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presences_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `direction_id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_code_unique` (`code`),
  KEY `services_direction_id_foreign` (`direction_id`),
  KEY `services_head_id_foreign` (`head_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `code`, `description`, `direction_id`, `head_id`, `created_at`, `updated_at`) VALUES
(1, 'Appui Informatique', 'AI', NULL, 1, NULL, '2025-12-04 11:13:33', '2025-12-04 11:13:33'),
(2, 'Service de la Documentation et des Activités', 'SDC', NULL, 1, NULL, '2025-12-04 11:16:33', '2025-12-04 11:16:33'),
(3, 'Service de la Production et de Diffusion des Statistiques', 'Sce SPDS', NULL, 1, NULL, '2025-12-04 11:18:28', '2025-12-04 11:18:28'),
(4, 'Service de la Plannification et de Suivi de la Performance', 'Sce SPSP', NULL, 2, NULL, '2025-12-04 11:21:56', '2025-12-04 11:21:56'),
(5, 'Service de la Veille Stratégique', 'Sce VS', NULL, 2, NULL, '2025-12-04 11:22:25', '2025-12-04 11:22:25'),
(6, 'Service de Etudes Fiscales', 'Sce EF', NULL, 3, NULL, '2025-12-04 11:23:11', '2025-12-04 11:23:11'),
(7, 'Service des Etudes Sectorielles et des Monographies', 'Sce ESM', NULL, 3, NULL, '2025-12-04 11:23:56', '2025-12-04 11:23:56'),
(8, 'Service des Simulations et des Evaluations', 'Sce SE', NULL, 3, NULL, '2025-12-04 11:24:39', '2025-12-04 11:24:39'),
(9, 'Service des Statistiques d\'Assiette et du Contrôle Fiscale', 'Sce SACF', NULL, 4, NULL, '2025-12-04 11:25:36', '2025-12-04 11:25:36'),
(10, 'Service d\'Analyse des Statistiques de Recettes Fiscales', 'Sce ASRF', NULL, 4, NULL, '2025-12-04 11:26:19', '2025-12-04 11:26:19'),
(11, 'Service des Prévision de Recettes et des Indicateurs Economiques', 'Sce PRIE', NULL, 4, NULL, '2025-12-04 11:27:19', '2025-12-04 11:27:19'),
(12, 'Service de Gestion et d\'Archivage des Etats Financiers', 'Sce GAEF', NULL, 5, NULL, '2025-12-04 11:28:03', '2025-12-04 11:28:03'),
(13, 'Service d\'Analyse et d\'Exploitation des Données', 'Sce AED', NULL, 5, NULL, '2025-12-04 11:28:34', '2025-12-04 11:28:34'),
(14, 'Service d\'Appui au Dépot en Ligne des Etats Financiers', 'Sce ADLEF', NULL, 5, NULL, '2025-12-04 11:29:43', '2025-12-04 11:29:43');

-- --------------------------------------------------------

--
-- Structure de la table `type_absences`
--

DROP TABLE IF EXISTS `type_absences`;
CREATE TABLE IF NOT EXISTS `type_absences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_type` enum('Congé','Repos Maladie','Mission','Permission','Autres') NOT NULL DEFAULT 'Congé',
  `code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `est_paye` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_courriers`
--

DROP TABLE IF EXISTS `type_courriers`;
CREATE TABLE IF NOT EXISTS `type_courriers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `bio`, `profile_picture`) VALUES
(1, 'Coulibaly Sie Yacouba', 'coulsie@gmail.com', NULL, '$2y$12$WAjiSLgNkjOq52FJwCm5dO0HMHY8HyP1aA5dMArsLqFT.kvZTzvvy', 'LlzUSHOwWyD5neqebsxnUyXKLJewGsZbnhUQ8Up0nsQDfLuh3vULnJz9PZRo', '2025-11-17 17:37:48', '2025-11-17 17:37:48', NULL, NULL),
(2, 'YAKOU', 'coulsie@live.fr', NULL, '$2y$12$oiR9M0/fgELOtm.bvJIq9eAJFy8K3x7sa.exwZW9DUNC.otfOFvBG', NULL, '2025-12-01 12:07:44', '2025-12-01 12:07:44', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
