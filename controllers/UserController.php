<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/request.php';

class UserController extends BaseController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * ADMIN
     * POST /api/users/create
     */
    public function create(): void
    {
        $this->auth('admin');
        $input = request();

        if (
            empty($input['name']) ||
            empty($input['email']) ||
            empty($input['password']) ||
            empty($input['role'])
        ) {
            jsonResponse(false, 'All fields are required', 422);
        }

        if ($this->user->findByEmail($input['email'])) {
            jsonResponse(false, 'Email already exists', 409);
        }

        $id = $this->user->create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'role'     => $input['role']
        ]);

        jsonResponse(true, 'User created', 201, ['id' => $id]);
    }

    /**
     * USER / ADMIN
     * GET /api/users/me
     */
    public function me(): void
    {
        $auth = $this->auth();
        $user = $this->user->findById($auth['id']);

        jsonResponse(true, 'User profile', 200, $user);
    }

    /**
     * ADMIN
     * GET /api/users/all
     */
    public function all(): void
    {
        $this->auth('admin');
        $users = $this->user->getAll();

        jsonResponse(true, 'All users', 200, $users);
    }

    /**
     * USER / ADMIN
     * PUT /api/users/update
     */
    public function update(): void
    {
        $auth  = $this->auth();
        $input = request();

        $data = [];

        if (!empty($input['name'])) {
            $data['name'] = $input['name'];
        }

        if (!empty($input['password'])) {
            $data['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        if (empty($data)) {
            jsonResponse(false, 'No data to update', 422);
        }

        $this->user->update($auth['id'], $data);

        jsonResponse(true, 'User updated');
    }

    /**
     * ADMIN
     * DELETE /api/users/delete?id=1
     */
    public function delete(int $id): void
    {
        $this->auth('admin');

        if (!$this->user->findById($id)) {
            jsonResponse(false, 'User not found', 404);
        }

        $this->user->delete($id);

        jsonResponse(true, 'User deleted');
    }
}