<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

(new AuthController())->logout();