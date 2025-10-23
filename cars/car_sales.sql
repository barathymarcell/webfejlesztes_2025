CREATE DATABASE IF NOT EXISTS car_sales CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_sales;

DROP TABLE IF EXISTS ratings;
DROP TABLE IF EXISTS cars;
DROP TABLE IF EXISTS accounts;

CREATE TABLE accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

CREATE TABLE cars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(100) NOT NULL,
  model VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  reserved_by INT DEFAULT NULL,
  CONSTRAINT fk_reserved_by FOREIGN KEY (reserved_by) REFERENCES accounts(id) ON DELETE SET NULL
);

CREATE TABLE ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  UNIQUE (car_id, user_id),
  FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES accounts(id) ON DELETE CASCADE
);

INSERT INTO cars (brand, model, price) VALUES
('Toyota', 'Corolla', 16000.00),
('Honda', 'Civic', 18000.00),
('Ford', 'Focus', 14000.00);
