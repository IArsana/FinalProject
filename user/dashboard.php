<?php

require_once __DIR__ . '/../auth/middleware.php';
require_once __DIR__ . '/../helpers/response.php';

/**
 * LOGIN REQUIRED
 */
$user = auth();

jsonResponse(
    true,
    'Welcome User',
    200,
    [
        'user_id' => $user['id'],
        'name'    => $user['name'],
        'role'    => $user['role']
    ]
);