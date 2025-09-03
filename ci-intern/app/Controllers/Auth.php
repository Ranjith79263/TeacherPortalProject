<?php
namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AuthUserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    protected $format = 'json';
    public function __construct()
    {
        
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Origin: http://localhost:3000");

    }


    // ✅ Register API
    public function register()
    {
        $model = new AuthUserModel();

        // Support JSON body and form-encoded
        $input = $this->request->getJSON(true);
        if (!is_array($input) || empty($input)) {
            $input = [
                'email' => $this->request->getPost('email'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'password' => $this->request->getPost('password'),
            ];
        }

        $email = $input['email'] ?? null;
        $firstName = $input['first_name'] ?? null;
        $lastName = $input['last_name'] ?? null;
        $passwordPlain = $input['password'] ?? null;

        if (!$email || !$firstName || !$lastName || !$passwordPlain) {
            return $this->failValidationErrors('Missing required fields: email, first_name, last_name, password');
        }

        try {
            $data = [
                'email'      => $email,
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'password'   => password_hash($passwordPlain, PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $model->insert($data);
        } catch (\Throwable $e) {
            return $this->failServerError('Registration failed: ' . $e->getMessage());
        }

        return $this->respondCreated(['message' => 'User Registered Successfully']);
    }

    // User Login
public function login()
{
    $model = new AuthUserModel();
    $input = $this->request->getJSON(true);
    $email = $input['email'] ?? null;
    $password = $input['password'] ?? null;

    if (!$email || !$password) {
        return $this->failValidationErrors('Missing email or password');
    }


    $user = $model->where('email', $email)->first();

    if (!$user || !password_verify($password, $user['password'])) {
        return $this->failUnauthorized('Invalid email or password');
    }

    // Generate JWT Token
    $payload = [
        'id'    => $user['id'],
        'email' => $user['email'],
        'iat'   => time(),
        'exp'   => time() + 3600  // 1 hour expiration
    ];

    $token = \Firebase\JWT\JWT::encode($payload, JWT_SECRET, 'HS256');

    return $this->respond(['token' => $token]);
}
// Profile (Protected Route)
public function profile()
{
    $authHeader = $this->request->getHeaderLine('Authorization');

    if (!$authHeader) {
        return $this->failUnauthorized('Token missing');
    }

    $token = str_replace('Bearer ', '', $authHeader);

    try {
        $decoded = \Firebase\JWT\JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
    } catch (\Throwable $e) {
        return $this->failUnauthorized('Invalid or expired token: ' . $e->getMessage());
    }

    return $this->respond(['profile' => $decoded]);
}
}
