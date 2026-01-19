===============================
ABSENSI API SYSTEM (DOCKER)
===============================

DESKRIPSI:
-----------
Sistem absensi berbasis PHP dengan JWT untuk autentikasi dan role-based access.
Terdapat dua role: 'admin' dan 'user'. Fitur utama:

- Login dengan email & password
- JWT-based authentication
- Check-in & Check-out
- Attendance history (user dan admin)
- Admin-only access untuk melihat semua data absensi

---

STRUKTUR FOLDER:
-----------------
/api/attendance/      # Endpoint attendance (checkin, checkout, history)
/api/users/           # Endpoint user (login, CRUD)
/auth/                # Middleware JWT
/config/              # Koneksi database dan JWT helper
/controllers/         # Controller Attendance & User
/helpers/             # JSON response helper
/models/              # BaseModel, Attendance, User
/admin/               # Dashboard admin
/user/                # Dashboard user
/vendor/              # Composer dependencies (firebase/php-jwt)
docker/               # Dockerfile & MySQL init.sql

---

DOCKER SETUP:
--------------
1. Pastikan Docker & Docker Compose terpasang.
2. Build dan jalankan container:

   docker-compose up --build

3. Container akan menjalankan:
   - PHP + Apache pada port 5000
   - MySQL pada port 3307 (host) -> 3306 (container)

4. MySQL database otomatis di-inisialisasi dari:
   docker/mysql/init.sql
   Database: absensi_db
   Tabel: users, attendance
   Default admin: admin@mail.com / password default

---

ENVIRONMENT VARIABLES (.env):
-------------------------------
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=root
DB_PASSWORD=your_password
JWT_SECRET=some_secret_key

---

DATABASE SCHEMA:
----------------
CREATE DATABASE IF NOT EXISTS absensi_db;

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

-- Default admin
INSERT INTO users (name,email,password,role)
VALUES (
    'Admin',
    'admin@mail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);

---

API ENDPOINTS:
---------------
1. POST /api/users/login
   Body: { "email": "...", "password": "..." }
   Response: { "token": "...", "user": {id, name, email, role} }

2. POST /api/attendance/checkin
   Header: Authorization: Bearer {token}

3. POST /api/attendance/checkout
   Header: Authorization: Bearer {token}

4. GET /api/attendance/me
   Header: Authorization: Bearer {token}

5. GET /api/attendance/all
   Header: Authorization: Bearer {token} (admin only)

6. Admin dashboard: /admin/dashboard.php
   Header: Authorization: Bearer {token} (role admin only)

7. User dashboard: /user/dashboard.php
   Header: Authorization: Bearer {token}

---

NOTES:
-------
- JWT berlaku 1 hari.
- Check-in hanya bisa 1 kali per hari per user.
- Check-out hanya bisa dilakukan jika check-in sudah ada.
- Role-based access diterapkan di middleware.
- Semua response menggunakan format JSON:
  {
    "success": true|false,
    "message": "Some message",
    "data": {...} | null
  }

---

DOCKER COMMANDS:
-----------------
- Build & run containers:
  docker-compose up --build

- Stop containers:
  docker-compose down

- Rebuild container setelah perubahan:
  docker-compose up --build -d

- Masuk ke container PHP:
  docker exec -it absensi_web bash

- Masuk ke container MySQL:
  docker exec -it absensi_mysql mysql -uroot -p