CREATE DATABASE IF NOT EXISTS viacao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE viacao;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(40),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE routes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  origin VARCHAR(150) NOT NULL,
  destination VARCHAR(150) NOT NULL,
  base_price DECIMAL(8,2) NOT NULL,
  duration_minutes INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE buses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  seats INT DEFAULT 40
);

CREATE TABLE seat_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  multiplier DECIMAL(3,2) NOT NULL,
  description TEXT
);

CREATE TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  route_id INT NOT NULL,
  bus_id INT NOT NULL,
  seat_number VARCHAR(10) NOT NULL,
  seat_type_id INT NOT NULL,
  price DECIMAL(8,2) NOT NULL,
  status ENUM('booked','paid','cancelled') DEFAULT 'booked',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE,
  FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE,
  FOREIGN KEY (seat_type_id) REFERENCES seat_types(id) ON DELETE CASCADE
);

CREATE TABLE drivers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  years_experience INT DEFAULT 0,
  phone VARCHAR(40),
  bus_id INT DEFAULT NULL,
  photo VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE SET NULL
);

INSERT INTO routes (origin,destination,base_price,duration_minutes) VALUES
('São Paulo','Rio de Janeiro',69.00,270),
('Salvador','Jericoacoara',199.90,600),
('São Luís','Lençóis Maranhenses',99.90,300),
('Bonito','Campo Grande',79.90,180);

INSERT INTO buses (name,seats) VALUES ('Marcopolo 1',40),('Marcopolo 2',40);

INSERT INTO seat_types (name, multiplier, description) VALUES
('Leito', 1.00, 'Assento reclinável com maior conforto'),
('Semi-leito', 1.10, 'Conforto intermediário com reclinação parcial'),
('Executivo', 1.20, 'Máximo conforto com poltrona premium');

CREATE TABLE cities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL UNIQUE,
  latitude DECIMAL(10,6),
  longitude DECIMAL(10,6),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Localizações padrão
INSERT IGNORE INTO cities (name, latitude, longitude) VALUES
('São Paulo', -23.5505, -46.6333),
('Rio de Janeiro', -22.9068, -43.1729),
('Salvador', -12.9714, -38.5014),
('Jericoacoara', -2.7933, -40.5131),
('São Luís', -2.5307, -44.3068),
('Lençóis Maranhenses', -2.5167, -43.1667),
('Bonito', -21.1300, -56.4822),
('Campo Grande', -20.4697, -54.6201);