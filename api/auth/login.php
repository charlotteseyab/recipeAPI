<?php
include_once '../../core/initialize.php'; // Ensure this loads the .env file and initializes environment variables
include_once '../../includes/headers.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Method Not Allowed']);
    exit;
}

// Get the input data
$data = json_decode(file_get_contents("php://input"));

// Validate input data
if (empty($data->email) || empty($data->password)) {
    http_response_code(400); // Bad request
    echo json_encode(['message' => 'Login failed: Missing required fields']);
    exit;
}

// Create a new User object
$user = new User($db);
$user->email = $data->email;
$user->password = $data->password;

// Attempt to log in the user
if ($user->login()) { // Assuming the login method checks credentials and sets user ID
    $id = $user->id;

    // Generate the token
    try {
        $jwtHandler = new JWTHandler();
        $token = $jwtHandler->encode(['id' => $id]);
        echo json_encode(['message' => 'Login successful', 'token' => $token]);
    } catch (Exception $e) {
        error_log('Token generation failed: ' . $e->getMessage());
        echo json_encode(['message' => 'Login successful, but token generation failed']);
    }
} else {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Login failed: Invalid email or password']);
}
?>