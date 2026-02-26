DROP DATABASE IF EXISTS sawm_lab7;
CREATE DATABASE sawm_lab7 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sawm_lab7;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('administrator', 'manager') NOT NULL
);

INSERT INTO user (login, password, role) VALUES
('admin', '$argon2id$v=19$m=65536,t=4,p=1$SkRZenNyQnpDSTVvNHlFdA$8of9lbvPAqzmW3vyzfha1VD4utgWAhGtObXZcgGFj40', 'administrator'),
('manager', '$argon2id$v=19$m=65536,t=4,p=1$SUtoMUJtc043czVkWGFSZQ$zbZK8p5rae5C2haXMccs7SA9ZHiadvxGZav6VmEhTMw', 'manager'),
('alex', '$argon2id$v=19$m=65536,t=4,p=1$cmtmVnlCQU1MV2Y1SUJicw$dnlE9UbbkM0WeS5vbzAaOe7cDoDPxu6DZSYCmW+xTlQ', 'manager'),
('olga', '$argon2id$v=19$m=65536,t=4,p=1$VW44S0k5cHhSL052MVNrWA$BGOMq04KCbJrnOS2rnR9ocCGG/ZvsDLOF8hHARmd8+0', 'manager'),
('nina', '$argon2id$v=19$m=65536,t=4,p=1$Qkx3OG1nNm9kUG51NGRSdw$VGK9TP/mMdLlYZILoI4xbc5n3mjDDzPL1xYKHf2CuHs', 'manager'),
('sergey', '$argon2id$v=19$m=65536,t=4,p=1$MW56WERlb3lLMzMyUTFqLw$tH5RyWyXBld+Tg/PUnyVwwHNAXe1ftP5b2sBUE1CMvM', 'manager'),
('guest1', '$argon2id$v=19$m=65536,t=4,p=1$UEhzMTMxc0tOQWFFYzV3ag$qT6ECoVtMGXMUN4uxcb8ZX6eJbpuFET64aPM4Je6DRs', 'manager');

CREATE TABLE guest (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    text_message TEXT NOT NULL,
    e_mail VARCHAR(120) NOT NULL,
    data_time_message DATETIME NOT NULL
);

CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(32) NOT NULL UNIQUE
);

INSERT INTO role (code) VALUES
('administrator'),
('manager');

CREATE TABLE permission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(64) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL
);

INSERT INTO permission (code, description) VALUES
('panel.admin', 'Access to admin panel'),
('panel.manager', 'Access to manager panel'),
('accounts.view', 'View accounts list'),
('accounts.edit', 'Edit account data'),
('accounts.delete', 'Delete account'),
('logs.view', 'View action logs'),
('errors.view', 'View error log');

CREATE TABLE role_permission (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permission_role FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permission_permission FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE
);

INSERT INTO role_permission (role_id, permission_id)
SELECT r.id, p.id
FROM role r
JOIN permission p
WHERE (r.code = 'administrator' AND p.code IN ('panel.admin', 'logs.view', 'errors.view'))
   OR (r.code = 'manager' AND p.code IN ('panel.manager', 'accounts.view', 'accounts.edit', 'accounts.delete'));
