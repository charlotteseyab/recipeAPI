<?php
require __DIR__ . '/../vendor/autoload.php';
include_once 'database.php';
include_once __DIR__ . '/../core/jwt.php';
include_once __DIR__ . '/../models/Recipe.php';
include_once __DIR__ . '/../models/User.php';



$database = new Database();
$db = $database->connect();
// try {
//     $jwtHandler = new JWTHandler();
// } catch (Exception $e) {
//     // Handle the exception, log it, and return an appropriate response
//     error_log($e->getMessage()); // Log the error message
//     error_log('JWTHandler initialization failed: ' . $e); // Additional logging for debugging
//     error_log('Stack trace: ' . $e->getTraceAsString()); // Log the stack trace for more context
//     http_response_code(500); // Set HTTP response code to 500 Internal Server Error
//     echo json_encode(['error' => 'Internal Server Error']); // Return a JSON error response
//     exit; // Stop further execution
// }
