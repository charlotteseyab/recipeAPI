<?php
include_once '../../core/initialize.php';
include_once '../../includes/headers.php';

// try {
//     $jwtHandler = new JWTHandler();
// } catch (Exception $e) {
//     error_log('JWTHandler initialization failed: ' . $e->getMessage());
//     http_response_code(500);
//     echo json_encode(['error' => 'Internal Server Error']);
//     exit;
// }

$data = json_decode(file_get_contents("php://input"));

// Check if JWT_SECRET_KEY is set before proceeding
if (!getenv('JWT_SECRET_KEY')) {
    error_log('JWT_SECRET_KEY not set in environment variables.');
    echo json_encode(['message' => 'Registration failed: JWT_SECRET_KEY not set']);
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
        $token = $jwtHandler->encode(['id' => $id, 'exp' => time() + 3600]);
        echo json_encode(['message' => 'User registered', 'token' => $token]);
    } catch (Exception $e) {
        error_log('Token generation failed: ' . $e->getMessage());
        echo json_encode(['message' => 'User registered, but token generation failed']);
    }
} else {
    echo json_encode(['message' => 'Registration failed: Email already exists']);
}