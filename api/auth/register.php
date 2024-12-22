<?php
include_once '../../core/initialize.php';
include_once '../../includes/headers.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
    exit;
}

// Check if JWT_SECRET_KEY is set before proceeding
// if (empty(getenv('JWT_SECRET_KEY'))) {
//     error_log('JWT_SECRET_KEY not set in environment variables.');
//     http_response_code(500);
//     echo json_encode(['message' => 'Registration failed: JWT_SECRET_KEY not set']);
//     exit;
// }

$data = json_decode(file_get_contents("php://input"));

// Validate input data
if (empty($data->name) || empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(['message' => 'Registration failed: Missing required fields']);
    exit;
}

$user = new User($db);
$user->name = $data->name;
$user->email = $data->email;
$user->password = $data->password;

error_log('User data: ' . json_encode($user));

if ($user->register()) {
    $id = $user->id;
    
    // Attempt to generate the token
    try {
        $jwtHandler = new JWTHandler();
        $token = $jwtHandler->encode(['id' => $id]);
        echo json_encode(['message' => 'User registered', 'token' => $token]);
    } catch (Exception $e) {
        error_log('Token generation failed: ' . $e->getMessage());
        echo json_encode(['message' => 'User registered, but token generation failed']);
    }
} else {
    http_response_code(409);
    echo json_encode(['message' => 'Registration failed: Email already exists']);
}
