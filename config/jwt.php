<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT(array $user): string
{
    $payload = [
        'iss' => 'attendance-api',
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24), // 1 hari
        'user' => [
            'id'    => $user['id'],
            'email' => $user['email'],
            'name'  => $user['name'],
            'role'  => $user['role']
        ]
    ];

    return JWT::encode(
        $payload,
        $_ENV['JWT_SECRET'],
        'HS256'
    );
}

function verifyJWT(string $token): array
{
    try {
        $decoded = JWT::decode(
            $token,
            new Key($_ENV['JWT_SECRET'], 'HS256')
        );

        return json_decode(json_encode($decoded), true); // array

    } catch (Exception $e) {
        throw $e;
    }
}

