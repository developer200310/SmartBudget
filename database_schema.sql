-- SmartBudget Database Schema
-- MySQL Database Structure

CREATE DATABASE IF NOT EXISTS smartbudget CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smartbudget;

-- Users Table
CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','gestionnaire','admin') DEFAULT 'user',
  avatar_url VARCHAR(255),
  id_budget INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Budgets Table
CREATE TABLE budgets (
  id_budget INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(150),
  total_revenus DOUBLE DEFAULT 0,
  total_depenses DOUBLE DEFAULT 0,
  epargne DOUBLE DEFAULT 0,
  objectif DOUBLE DEFAULT 0,
  periode_debut DATE,
  periode_fin DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Foreign Key for users -> budgets
ALTER TABLE users
  ADD CONSTRAINT fk_users_budget FOREIGN KEY (id_budget) REFERENCES budgets(id_budget)
  ON DELETE SET NULL ON UPDATE CASCADE;

-- Transactions Table
CREATE TABLE transactions (
  id_tx INT AUTO_INCREMENT PRIMARY KEY,
  budget_id INT NOT NULL,
  user_id INT NOT NULL,
  type ENUM('depense','revenu') NOT NULL,
  categorie VARCHAR(100),
  montant DOUBLE NOT NULL,
  date_tx DATE NOT NULL,
  description TEXT,
  recurring TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (budget_id) REFERENCES budgets(id_budget) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE
);

-- Posts Table
CREATE TABLE posts (
  id_post INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT NOT NULL,
  titre VARCHAR(200),
  contenu TEXT,
  type ENUM('objectif','partage','question') DEFAULT 'partage',
  likes_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id_user) ON DELETE CASCADE
);

-- Comments Table
CREATE TABLE comments (
  id_comment INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  author_id INT NOT NULL,
  contenu TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id_post) ON DELETE CASCADE,
  FOREIGN KEY (author_id) REFERENCES users(id_user) ON DELETE CASCADE
);

-- Likes Table
CREATE TABLE likes (
  id_like INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_like (post_id, user_id),
  FOREIGN KEY (post_id) REFERENCES posts(id_post) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE
);

-- Notifications Table
CREATE TABLE notifications (
  id_notif INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type VARCHAR(100),
  payload JSON,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE
);
