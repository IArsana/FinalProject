<?php

require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../auth/middleware.php';

abstract class BaseController
{
    protected function auth(?string $role = null): array
    {
        return auth($role);
    }
}