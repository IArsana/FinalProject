<?php

require_once __DIR__ . '/../auth/middleware.php';
require_once __DIR__ . '/../helpers/response.php';

// ambil user dari JWT
$currentUser = auth();

if ($currentUser['role'] !== 'admin') {
    jsonResponse(
        false,
        'Access denied',
        data: "Error 403"
    );
}

jsonResponse(
    true,
    'Welcome Admin',
    200,
    [
        'id' => $currentUser['id'],
        'role'    => $currentUser['role']
    ]
);