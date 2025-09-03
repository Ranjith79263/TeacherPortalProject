<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TeacherModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Teacher extends ResourceController
{
    protected $format = 'json';

    // Add teacher dynamically via URL parameters
    // Example URL: http://localhost:8080/teacher/add?user_id=1&university_name=XYZ&gender=male&year_joined=2020
    public function add()
    {
        $model = new TeacherModel();

        $user_id = $this->request->getGet('user_id');
        $university_name = $this->request->getGet('university_name');
        $gender = $this->request->getGet('gender');
        $year_joined = $this->request->getGet('year_joined');

        if (!$user_id || !$university_name || !$gender || !$year_joined) {
            return $this->failValidationErrors('Missing required parameters!');
        }

        $data = [
            'user_id' => $user_id,
            'university_name' => $university_name,
            'gender' => $gender,
            'year_joined' => $year_joined,
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $model->insert($data);
        } catch (\Throwable $e) {
            return $this->failServerError('Failed to add teacher: ' . $e->getMessage());
        }

        return $this->respondCreated(['message' => 'Teacher Added!']);
    }

    // View all teachers (Protected route)
    public function index()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader) {
            return $this->failUnauthorized('Token missing');
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        } catch (\Throwable $e) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $model = new TeacherModel();
        $teachers = $model->findAll();

        return $this->respond($teachers);
    }
}
