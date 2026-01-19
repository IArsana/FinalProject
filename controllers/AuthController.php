<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/request.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../auth/session.php';

class AuthController extends BaseController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function login(): void
    {
        $input = request();

        if (empty($input['email']) || empty($input['password'])) {
            jsonResponse(false, 'Email & password required', 422);
        }

        $user = $this->user->findByEmail($input['email']);

        if (!$user || !password_verify($input['password'], $user['password'])) {
            jsonResponse(false, 'Invalid credentials', 401);
        }

        $_SESSION['user_id'] = $user['id'];
        $token = generateJWT($user);

        jsonResponse(true, 'Login success', 200, [
            'token' => $token,
            'user'  => $user
        ]);
    }

    public function logout(): void
    {
        session_destroy();
        jsonResponse(true, 'Logout success');
    }
}