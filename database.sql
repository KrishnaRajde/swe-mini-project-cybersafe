CREATE DATABASE IF NOT EXISTS cybersafe;
USE cybersafe;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  quiz_score INT DEFAULT 0,
  photo VARCHAR(255) NULL,
  learn_completed TINYINT(1) NOT NULL DEFAULT 0,
  analyzer_completed TINYINT(1) NOT NULL DEFAULT 0,
  generator_completed TINYINT(1) NOT NULL DEFAULT 0,
  phishing_completed TINYINT(1) NOT NULL DEFAULT 0,
  quiz_completed TINYINT(1) NOT NULL DEFAULT 0
);
