<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setSession(array $user): void
{
    $_SESSION['user'] = [
        'id'   => $user['id'],
        'role' => $user['role']
    ];
}

function getSession(): ?array
{
    return $_SESSION['user'] ?? null;
}

function destroySession(): void
{
    session_unset();
    session_destroy();
}