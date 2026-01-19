<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../controllers/AttendanceController.php';

(new AttendanceController())->checkOut();