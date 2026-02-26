DROP DATABASE IF EXISTS sawm_lab1;
CREATE DATABASE sawm_lab1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sawm_lab1;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO user (login, password) VALUES
('admin', 'admin123'),
('manager', 'manager123'),
('alex', 'alex123'),
('olga', 'olga123'),
('nina', 'nina123'),
('sergey', 'sergey123'),
('guest1', 'guest123');

