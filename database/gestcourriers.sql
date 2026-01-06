-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mar. 06 jan. 2026 à 16:44
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `absences`
--

INSERT INTO `absences` (`id`, `agent_id`, `type_absence_id`, `date_debut`, `date_fin`, `approuvee`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2025-12-15', '2025-12-22', 0, '2025-12-14 11:29:29', '2025-12-14 11:29:29');

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
  UNIQUE KEY `id` (`id`),
  KEY `affectations_courrier_id_foreign` (`courrier_id`),
  KEY `affectations_user_id_foreign` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `affectations`
--

INSERT INTO `affectations` (`id`, `courrier_id`, `agent_id`, `statut`, `commentaires`, `date_affectation`, `date_traitement`, `created_at`, `updated_at`) VALUES
(7, 12, 3, 'in_progress', NULL, '2025-12-11 09:57:43', '2025-12-11 09:57:43', '2025-12-11 09:57:43', '2025-12-11 09:57:43'),
(23, 2, 1, 'en_cours', 'BONJOUR', '2026-01-06 16:41:00', '2026-01-14 16:42:00', '2026-01-06 16:42:11', '2026-01-06 16:42:11');

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
  `place_birth` varchar(191) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `email_professionnel`, `matricule`, `first_name`, `last_name`, `status`, `sexe`, `date_of_birth`, `place_birth`, `photo`, `email`, `phone_number`, `address`, `Emploi`, `Grade`, `Date_Prise_de_service`, `Personne_a_prevenir`, `Contact_personne_a_prevenir`, `service_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'coulsie@live.fr', '287688C', 'Sié Yacouba', 'COULIBALY', 'Chef de service', 'Female', '1972-12-04', 'COCODY', 'C:\\wamp64\\tmp\\phpE50B.tmp', 'coulsie@gmail.com', '0707584396', '08 BP 2359', 'Inspecteur Principal Informatique', 'A6', '2002-01-05', 'COULIBALY Youssef Kiyali', '0143677424', 1, 1, '2025-12-04 13:36:01', '2025-12-19 16:50:38'),
(2, NULL, '410702H', 'Nafata', 'KONE', 'Agent', NULL, NULL, NULL, '/storage/storage/agents_photos/kss1wbQcDFBK8pleueqQJdrzUjwrM318BitCWUHF.jpg', NULL, '0707188674', 'Grand Bassam mockeyville', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-12-09 15:06:21', '2025-12-16 10:53:36'),
(3, NULL, '421263X', 'SIAKOURI', 'Justine', 'Agent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, '2025-12-09 15:58:42', '2025-12-09 15:58:42'),
(4, 'vb@10klhg', '100600A', 'KOMIAN', 'Anselm', 'Chef de service', 'Male', NULL, NULL, '/storage/storage/agents_photos/wgoMB7Fhj32h9pNnqs2rjAucRMbG2FQKB2WMC6Vx.jpg', 'coulsie@live.fr', NULL, NULL, NULL, NULL, NULL, 'amoin', '010124578', 13, NULL, '2025-12-12 14:43:58', '2025-12-12 15:07:15'),
(5, 'vb@gmail.com', '100501F', 'KONAN', 'Aya', 'Chef de service', 'Female', '1968-01-05', NULL, NULL, 'konan@aya25', NULL, NULL, NULL, NULL, NULL, 'aurore', '125849637', 7, NULL, '2025-12-12 21:48:34', '2025-12-12 21:48:34'),
(6, 'bgff@HGJK', '100235M', 'SYLLA', 'Youssouf', 'Sous-directeur', 'Male', '1980-01-01', 'ISSIA', '/storage/agents_photos/92U7VhTeiYCNOIFRi565I9tHSvcwcc4CEkAFM8HA.jpg', 'youssouf@gmail.com', NULL, NULL, NULL, 'A6', NULL, 'HHHH', '455888', 6, NULL, '2025-12-13 10:23:37', '2025-12-13 10:23:37'),
(7, 'didier@45', '100542Q', 'CAMARA', 'Naotchin Didier', 'Directeur', 'Male', '1971-02-01', 'DIVO', '/storage/agents_photos/81UJDuSmCHcDhWrFyp4t2TtAjvnVeJSD6RZMpHHi.jpg', 'Sieben@v', '225 0707584396', '08 BP 2359', NULL, 'A7', NULL, 'rosalie45', '25665666', 14, NULL, '2025-12-13 10:49:55', '2025-12-13 10:49:55'),
(8, 'sylva@gmail.com', '100222A', 'KOFFI', 'Sylvanus', 'Chef de service', 'Male', '1987-12-04', 'YAMOUSSOUKRO', '/storage/agents_photos/A7LUuO7BCx80Nr7rlfrZWMlaesD1Kg8T8juKDc5Z.jpg', 'sylvanus@yahoo.fr', '0584365858', '01BP4125', 'Ingenieur statiticien economiste', 'A5', '2007-01-01', 'Koffi rita', '55525666', 5, NULL, '2025-12-13 16:31:12', '2025-12-13 16:31:12');

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(191) NOT NULL,
  `contenu` text NOT NULL,
  `type` enum('urgent','information','evenement','avertissement','general') NOT NULL DEFAULT 'general',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `titre`, `contenu`, `type`, `is_active`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Réunion de comité', '10 h  ce jour salle de conférence', 'urgent', 1, NULL, '2026-01-04 12:43:52', '2026-01-04 12:43:52'),
(2, 'Atelier sur la GED', 'Atelier de formation sur la gestion electronique des documents le vendredi 9 janvier 2026 à la salle de conférence au 6ème etage', 'evenement', 1, NULL, '2026-01-04 13:00:53', '2026-01-04 13:00:53');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `courriers`
--

DROP TABLE IF EXISTS `courriers`;
CREATE TABLE IF NOT EXISTS `courriers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `affecter` tinyint(1) NOT NULL DEFAULT 0,
  `reference` varchar(255) DEFAULT NULL,
  `type` enum('Incoming','Outgoing','Information','Other_possible_value') DEFAULT NULL,
  `objet` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_courrier` date DEFAULT NULL,
  `expediteur_nom` varchar(255) NOT NULL DEFAULT 'non spécifié',
  `expediteur_contact` varchar(255) DEFAULT NULL,
  `destinataire_nom` varchar(255) NOT NULL DEFAULT 'Valeur par défaut',
  `destinataire_contact` varchar(255) DEFAULT NULL,
  `statut` enum('reçu','en_traitement','traité','archivé','affecté') NOT NULL DEFAULT 'reçu',
  `assigne_a` varchar(255) DEFAULT NULL,
  `chemin_fichier` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courriers_reference_unique` (`reference`),
  KEY `courriers_assigne_a_foreign` (`assigne_a`(250))
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `courriers`
--

INSERT INTO `courriers` (`id`, `affecter`, `reference`, `type`, `objet`, `description`, `date_courrier`, `expediteur_nom`, `expediteur_contact`, `destinataire_nom`, `destinataire_contact`, `statut`, `assigne_a`, `chemin_fichier`, `created_at`, `updated_at`) VALUES
(2, 1, '0210', 'Outgoing', 'info', NULL, '2025-12-01', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'affecté', NULL, NULL, '2025-12-01 15:55:11', '2026-01-06 16:42:11'),
(3, 0, '1010', 'Outgoing', 'Information', NULL, '2025-12-03', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-03 16:37:56', '2025-12-03 16:37:56'),
(4, 0, '2134', 'Outgoing', 'Information', NULL, '2025-12-04', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-04 07:57:30', '2025-12-04 17:05:58'),
(6, 1, '203', 'Incoming', 'Information', NULL, '2025-12-04', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-04 07:58:40', '2026-01-06 16:20:52'),
(7, 1, '10425', 'Incoming', 'travail a faire', NULL, '2025-12-05', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'affecté', NULL, NULL, '2025-12-05 08:24:02', '2026-01-06 16:32:01'),
(8, 0, '275', 'Incoming', 'travail a faire', NULL, '2025-12-08', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-08 11:09:26', '2025-12-08 11:09:26'),
(9, 0, '21', 'Incoming', 'TAF', NULL, '2025-12-10', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-10 14:31:11', '2025-12-10 14:31:11'),
(10, 0, '20', 'Outgoing', 'TAF', NULL, '2025-12-10', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-10 14:41:14', '2025-12-10 14:41:14'),
(11, 0, '77', 'Outgoing', 'Avoir', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 09:07:18', '2025-12-11 09:07:18'),
(12, 0, '10', 'Outgoing', 'travail a faire', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 09:33:41', '2025-12-11 09:33:41'),
(13, 1, '281', 'Incoming', 'impots', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 10:37:07', '2026-01-06 16:26:20'),
(14, 0, '11121', 'Outgoing', 'TAF', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 12:21:08', '2025-12-11 12:21:08'),
(15, 0, '001', 'Outgoing', 'TAF', NULL, '2025-12-11', 'non spécifié', NULL, 'Valeur par défaut', NULL, 'reçu', NULL, NULL, '2025-12-11 12:21:38', '2025-12-11 12:21:38'),
(16, 0, '1111111011002025', 'Incoming', 'TAF', 'tpo', '2025-12-11', 'SAPH', '0707584396', 'S/D GUDEF', '1223566', 'reçu', 'COUL', NULL, '2025-12-11 14:01:42', '2025-12-11 14:01:42'),
(17, 0, '123588996', 'Incoming', 'bnh', 'hgfiuluoioiu', '2025-12-11', 'h,njkll', '014535536', 'bkjlkml', '254966', 'reçu', NULL, NULL, '2025-12-11 16:13:59', '2025-12-11 16:13:59'),
(18, 1, '24A', 'Incoming', 'Bon pour Travaux', 'Appréciation du Directeur', '2025-12-12', 'DMGE', '0584365858', 'DSESF', '254966', 'reçu', 'KOFFI', '243 ADJAME DALLAS 25 juin 2025 VERSION 1.docx', '2025-12-12 09:08:13', '2026-01-06 16:21:27');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `directions`
--

INSERT INTO `directions` (`id`, `name`, `code`, `description`, `head_id`, `created_at`, `updated_at`) VALUES
(1, 'Cabinet Directeur', 'Direction', 'Cabinet du Directeur de la DSESF', 1, '2025-12-04 09:31:33', '2025-12-13 23:45:44'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jour` varchar(191) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `tolerance_retard` int(11) NOT NULL DEFAULT 15,
  `est_ouvre` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `horaires`
--

INSERT INTO `horaires` (`id`, `jour`, `heure_debut`, `heure_fin`, `tolerance_retard`, `est_ouvre`, `created_at`, `updated_at`) VALUES
(1, 'Monday', '07:30:00', '16:30:00', 15, 1, '2026-01-05 16:31:50', '2026-01-05 16:31:50'),
(2, 'Tuesday', '07:30:00', '16:30:00', 15, 1, '2026-01-05 16:31:50', '2026-01-05 16:31:50'),
(3, 'Wednesday', '07:30:00', '16:30:00', 15, 1, '2026-01-05 16:31:50', '2026-01-05 16:31:50'),
(4, 'Thursday', '07:30:00', '16:30:00', 15, 1, '2026-01-05 16:31:50', '2026-01-05 16:31:50'),
(5, 'Friday', '07:30:00', '16:30:00', 15, 1, '2026-01-05 16:31:50', '2026-01-05 16:31:50'),
(6, 'Saturday', '00:00:00', '00:00:00', 0, 0, '2026-01-05 16:31:50', '2026-01-05 16:31:50'),
(7, 'Sunday', '00:00:00', '00:00:00', 0, 0, '2026-01-05 16:31:50', '2026-01-05 16:31:50');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(21, '2025_12_11_092142_change_statut_column_type_to_enum', 21),
(22, '2025_12_15_124007_add_document_to_notifications_taches_table', 22),
(23, '2025_12_15_145617_rename_id_agent_to_agent_id_in_notifications_taches_table', 23),
(24, '2025_12_15_155804_add_timestamps_to_notifications_taches_table', 24),
(25, '2025_12_16_154155_add_role_to_users_table', 25),
(26, '2025_12_23_132434_add_is_archived_to_notifications_taches_table', 26),
(27, '2025_12_23_142311_create_reponse_notifications_table', 27),
(28, '2025_12_23_153002_add_soft_deletes_to_notifications_taches_table', 28),
(29, '2026_01_02_105921_add_must_change_password_to_users_table', 29),
(30, '2026_01_04_120452_create_annonces_table', 30),
(31, '2026_01_05_151012_create_horaires_table', 31),
(32, '2026_01_06_151247_add_affecter_to_courriers_table', 32);

-- --------------------------------------------------------

--
-- Structure de la table `notifications_taches`
--

DROP TABLE IF EXISTS `notifications_taches`;
CREATE TABLE IF NOT EXISTS `notifications_taches` (
  `id_notification` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_echeance` timestamp NULL DEFAULT NULL,
  `suivi_par` varchar(100) NOT NULL,
  `priorite` enum('Faible','Moyenne','Élevée','Urgent') NOT NULL DEFAULT 'Moyenne',
  `statut` enum('Non lu','En cours','Complétée','Annulée') NOT NULL DEFAULT 'Non lu',
  `lien_action` varchar(512) DEFAULT NULL,
  `document` varchar(512) DEFAULT NULL,
  `date_lecture` timestamp NULL DEFAULT NULL,
  `date_completion` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_notification`),
  KEY `notifications_taches_id_agent_foreign` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications_taches`
--

INSERT INTO `notifications_taches` (`id_notification`, `agent_id`, `titre`, `description`, `date_creation`, `date_echeance`, `suivi_par`, `priorite`, `statut`, `lien_action`, `document`, `date_lecture`, `date_completion`, `created_at`, `updated_at`, `is_archived`, `deleted_at`) VALUES
(2, 2, 'Prjet recensement des agents', 'lmmùm', '2025-12-19 11:22:38', '2026-01-02 11:22:00', 'bnhj', 'Élevée', 'En cours', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(3, 1, 'prise en main de emeraude', 'hjjjkj', '2025-12-20 21:26:39', '2025-12-27 21:25:00', 'ettien', 'Moyenne', 'Non lu', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(4, 2, 'jardin de la cité administrative', 'gfhhjlkjklkl', '2026-01-06 10:58:15', '2026-01-07 10:57:00', 'Mme N\'doume', 'Moyenne', 'Non lu', NULL, 'documents/xyRsERo8HYlIrYBDjNVvz56pXyl4G598QjOS4raH.docx', NULL, NULL, NULL, NULL, 0, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('coulsie@gmail.com', '$2y$12$GK9NCUhTUFfBEBDeq7Yo2.NlsKLPvVzvQeqogMFQ7ONJlLb0TfpHu', '2025-12-18 15:26:58');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `presences`
--

INSERT INTO `presences` (`id`, `agent_id`, `heure_arrivee`, `heure_depart`, `statut`, `notes`, `created_at`, `updated_at`) VALUES
(21, 6, '2026-01-05 07:52:00', '2026-01-05 19:47:00', 'En Retard', NULL, '2026-01-05 16:48:14', '2026-01-05 16:48:14'),
(22, 7, '2026-01-05 07:40:00', '2026-01-05 19:51:00', 'Présent', NULL, '2026-01-05 16:52:17', '2026-01-05 16:52:17'),
(23, 7, '2026-01-05 08:56:00', '2026-01-05 19:52:00', 'En Retard', NULL, '2026-01-05 16:53:04', '2026-01-05 16:53:04'),
(24, 1, '2026-01-05 07:52:00', '2026-01-05 20:56:00', 'En Retard', NULL, '2026-01-05 16:57:30', '2026-01-05 16:57:30');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_notifications`
--

DROP TABLE IF EXISTS `reponse_notifications`;
CREATE TABLE IF NOT EXISTS `reponse_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_notification` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `Reponse_Piece_jointe` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reponse_notifications_id_notification_foreign` (`id_notification`),
  KEY `reponse_notifications_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reponse_notifications`
--

INSERT INTO `reponse_notifications` (`id`, `id_notification`, `agent_id`, `message`, `Reponse_Piece_jointe`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'travail exécutée', NULL, '2025-12-28 11:20:56', '2025-12-28 11:20:56'),
(2, 3, 1, 'travail exécutée', NULL, '2025-12-28 11:21:08', '2025-12-28 11:21:08'),
(3, 3, 1, 'n,;;mm', NULL, '2025-12-28 11:39:05', '2025-12-28 11:39:05'),
(4, 3, 1, 'n,;;mm', NULL, '2025-12-28 11:40:26', '2025-12-28 11:40:26'),
(5, 3, 1, 'n,;;mm', NULL, '2025-12-28 11:41:38', '2025-12-28 11:41:38'),
(6, 3, 1, 'n,;:!ùù', NULL, '2025-12-28 11:42:11', '2025-12-28 11:42:11');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `code`, `description`, `direction_id`, `head_id`, `created_at`, `updated_at`) VALUES
(1, 'Appui Informatique', 'AI', 'ghjjkll', 1, 1, '2025-12-04 11:13:33', '2025-12-13 17:09:13'),
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_absences`
--

INSERT INTO `type_absences` (`id`, `nom_type`, `code`, `description`, `est_paye`, `created_at`, `updated_at`) VALUES
(1, 'Congé', 'CA', 'Congé Annuel auquel a droit chaque agent', 1, '2025-12-14 00:10:15', '2025-12-14 00:10:15'),
(2, 'Repos Maladie', 'RM', 'Repos maladie donné par un medecin', 1, '2025-12-14 00:11:30', '2025-12-14 00:11:30'),
(3, 'Mission', 'M', 'Mmission pour nécéssité de service', 1, '2025-12-14 00:12:11', '2025-12-14 00:12:11'),
(4, 'Permission', 'P', 'Permission d\'absence autorisé par le supérieur hiérarchique', 1, '2025-12-14 00:13:22', '2025-12-14 00:13:22'),
(5, 'Autres', 'Autre', 'permission d\'absence laissée à l\'appréciation de la hiérarchie', 1, '2025-12-14 00:14:32', '2025-12-14 00:14:32');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `must_change_password`, `remember_token`, `created_at`, `updated_at`, `bio`, `profile_picture`) VALUES
(1, 'Coulibaly Sie Yacouba', 'coulsie@gmail.com', 'admin', NULL, '$2y$12$WAjiSLgNkjOq52FJwCm5dO0HMHY8HyP1aA5dMArsLqFT.kvZTzvvy', 0, 'LlzUSHOwWyD5neqebsxnUyXKLJewGsZbnhUQ8Up0nsQDfLuh3vULnJz9PZRo', '2025-11-17 17:37:48', '2025-11-17 17:37:48', NULL, NULL),
(3, 'Yacoub', 'coulsie@live.fr', 'user', NULL, '$2y$12$XkM6OYaChSDICBzxhSgfkuJcBBUHxS8uUVzAVteO8PSoOgisJhvmm', 0, NULL, '2025-12-18 15:16:46', '2025-12-18 15:16:46', NULL, NULL),
(4, 'Maman', 'ouattanass@gmail.com', 'user', NULL, '$2y$12$OTuC6vrFt5lohvrby4P0qOKdaq5gOtzBu6yUpFwYS4kSHe1MtZVcq', 0, NULL, '2025-12-18 16:00:20', '2025-12-18 16:00:20', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
