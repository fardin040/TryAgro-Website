-- TryAgro Website database schema and seed data
-- Compatible with MySQL 8+ / MariaDB 10.4+

CREATE DATABASE IF NOT EXISTS `tryagro_website`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `tryagro_website`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admins_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `details` MEDIUMTEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_products_category_id` (`category_id`),
  CONSTRAINT `fk_products_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `dealers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `region` VARCHAR(120) NOT NULL,
  `district` VARCHAR(120) NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dealers_region_district` (`region`, `district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `videos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `youtube_link` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_name` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pages_page_name` (`page_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data
INSERT INTO `admins` (`username`, `password`)
VALUES ('admin', '$2y$12$KBWURG9zvJUxD8o0KMlw6uVFAW9Km/O2tcHh06qcjuHCPM0dBdql.')
ON DUPLICATE KEY UPDATE
  `password` = VALUES(`password`);

INSERT INTO `pages` (`page_name`, `content`)
VALUES
  ('homepage_intro', 'Welcome to TryAgro. We provide reliable agricultural products and practical support for modern farming.'),
  ('about_text', 'TryAgro is dedicated to helping farmers succeed with high-quality inputs, trusted guidance, and dependable service.'),
  ('contact_details', 'Phone: +880-1XXX-XXXXXX\nEmail: info@tryagro.example\nAddress: Dhaka, Bangladesh')
ON DUPLICATE KEY UPDATE
  `content` = VALUES(`content`);
