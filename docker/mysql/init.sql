CREATE DATABASE IF NOT EXISTS absensi_db;
USE absensi_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    check_in DATETIME,
    check_out DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- default admin
INSERT INTO users (name,email,password,role)
VALUES (
    'Admin',
    'admin@mail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);