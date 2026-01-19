<?php

function jsonResponse(
    bool $success,
    string $message,
    int $statusCode = 200,
    $data = null
): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ]);

    exit;
}