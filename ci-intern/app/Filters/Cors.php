<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $allowedOrigins = [
            "http://localhost:3000",
            "http://127.0.0.1:5500"
        ];

        $origin = $_SERVER['HTTP_ORIGIN'] ?? "";

        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
        }

        header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $allowedOrigins = [
            "http://localhost:3000",
            "http://127.0.0.1:5500"
        ];

        $origin = $_SERVER['HTTP_ORIGIN'] ?? "";

        if (in_array($origin, $allowedOrigins)) {
            $response->setHeader("Access-Control-Allow-Origin", $origin);
        }

        $response->setHeader("Access-Control-Allow-Headers", "Origin, Content-Type, Accept, Authorization");
        $response->setHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS, PUT, DELETE");

        return $response;
    }
}
