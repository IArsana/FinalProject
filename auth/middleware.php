<?php

require_once dirname(__DIR__) . '/helpers/response.php';
require_once dirname(__DIR__) . '/config/jwt.php';

function auth(?string $role = null): array
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        jsonResponse(false, 'Unauthorized (token missing)', 401);
    }

    if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        jsonResponse(false, 'Unauthorized (invalid token)', 401);
    }

    $token = $matches[1];

    try {
        $payload = verifyJWT($token);
    } catch (Exception $e) {
        jsonResponse(false, 'Unauthorized (token invalid)', 401);
    }

    if ($role && $payload['user']['role'] !== $role) {
        jsonResponse(false, 'Forbidden', 403);
    }

    return $payload['user'];
}