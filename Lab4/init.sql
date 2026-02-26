DROP DATABASE IF EXISTS sawm_lab4;
CREATE DATABASE sawm_lab4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sawm_lab4;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO user (login, password) VALUES
('admin', '$argon2id$v=19$m=65536,t=4,p=1$SkRZenNyQnpDSTVvNHlFdA$8of9lbvPAqzmW3vyzfha1VD4utgWAhGtObXZcgGFj40'),
('manager', '$argon2id$v=19$m=65536,t=4,p=1$SUtoMUJtc043czVkWGFSZQ$zbZK8p5rae5C2haXMccs7SA9ZHiadvxGZav6VmEhTMw'),
('alex', '$argon2id$v=19$m=65536,t=4,p=1$cmtmVnlCQU1MV2Y1SUJicw$dnlE9UbbkM0WeS5vbzAaOe7cDoDPxu6DZSYCmW+xTlQ'),
('olga', '$argon2id$v=19$m=65536,t=4,p=1$VW44S0k5cHhSL052MVNrWA$BGOMq04KCbJrnOS2rnR9ocCGG/ZvsDLOF8hHARmd8+0'),
('nina', '$argon2id$v=19$m=65536,t=4,p=1$Qkx3OG1nNm9kUG51NGRSdw$VGK9TP/mMdLlYZILoI4xbc5n3mjDDzPL1xYKHf2CuHs'),
('sergey', '$argon2id$v=19$m=65536,t=4,p=1$MW56WERlb3lLMzMyUTFqLw$tH5RyWyXBld+Tg/PUnyVwwHNAXe1ftP5b2sBUE1CMvM'),
('guest1', '$argon2id$v=19$m=65536,t=4,p=1$UEhzMTMxc0tOQWFFYzV3ag$qT6ECoVtMGXMUN4uxcb8ZX6eJbpuFET64aPM4Je6DRs');

CREATE TABLE guest (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    text_message TEXT NOT NULL,
    e_mail VARCHAR(120) NOT NULL,
    data_time_message DATETIME NOT NULL
);
