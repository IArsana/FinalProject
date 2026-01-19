<?php

require_once __DIR__ . '/../auth/middleware.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../helpers/response.php';

/**
 * NOTE:
 * - Auth & role check dilakukan DI CONTROLLER
 * - API layer hanya routing request
 */

$controller = new UserController();
$method     = $_SERVER['REQUEST_METHOD'];
$action     = $_GET['action'] ?? null;
$id         = $_GET['id'] ?? null;

switch ($method) {

    /**
     * POST /api/users/create
     */
    case 'POST':
        if ($action === 'create') {
            $controller->create();
            break;
        }

        jsonResponse(false, 'Invalid POST action', 400);

    /**
     * GET /api/users/me
     * GET /api/users/all
     */
    case 'GET':
        if ($action === 'me') {
            $controller->me();
            break;
        }

        if ($action === 'all') {
            $controller->all();
            break;
        }

        jsonResponse(false, 'Invalid GET action', 400);

    /**
     * PUT /api/users/update
     */
    case 'PUT':
        if ($action === 'update') {
            $controller->update();
            break;
        }

        jsonResponse(false, 'Invalid PUT action', 400);

    /**
     * DELETE /api/users/delete?id=1
     */
    case 'DELETE':
        if ($action === 'delete' && $id) {
            $controller->delete((int) $id);
            break;
        }

        jsonResponse(false, 'User ID required', 400);

    default:
        jsonResponse(false, 'Method not allowed', 405);
}