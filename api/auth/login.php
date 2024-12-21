<?php
require __DIR__ . '/../../vendor/autoload.php'; // Load Composer dependencies
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // Load environment variables
$dotenv->load();

header('Content-Type: application/json'); // Set content type to JSON

// Include the Database class
require __DIR__ . '/../../core/Database.php'; // Correct path to Database.php

// Initialize the database connection
$database = new Database();
$db = $database->getConnection(); // Get the PDO instance

$data = json_decode(file_get_contents("php://input")); // Decode JSON input

// Check if decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['message' => 'Invalid JSON input']);
    exit;
}

// Access email and password
$email = $data->email ?? null; // Use null coalescing operator to avoid undefined property error
$password = $data->password ?? null;

// Include the User class
require __DIR__ . '/../../models/User.php'; // Adjust the path as necessary

// Instantiate the User model
$user = new User($db); // Now $db is initialized

// Attempt to log in
if ($user->login($email, $password)) { // Assuming login method returns true on success
    // Include the JWTHandler class
    require __DIR__ . '/../../core/jwt.php'; 

    // Instantiate JWTHandler after successful login
    $jwtHandler = new JWTHandler(); // Ensure this is done after successful login
    $token = $jwtHandler->encode(['email' => $email, 'exp' => time() + 3600]); // Generate token
    echo json_encode(['message' => 'Login successful', 'token' => $token]);
} else {
    echo json_encode(['message' => 'Login failed: Invalid email or password']);
}
?>