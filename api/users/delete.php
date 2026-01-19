<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../controllers/UserController.php';

// Ambil ID dari query parameter
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
    exit;
}

$id = (int) $_GET['id']; // cast ke integer supaya tipe aman

// Jalankan delete
(new UserController())->delete($id);