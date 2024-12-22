<?php 
// namespace YourNamespace;

use Firebase\JWT\JWT;
use Dotenv\Dotenv;
// use Exception;

// Load the .env file from the root directory
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Debugging: Log the JWT_SECRET_KEY
error_log('JWT_SECRET_KEY: ' . getenv('JWT_SECRET_KEY'));



class JWTHandler {
    // Use the secret key from the .env file
    private $secret_key;

    public function __construct() {
        $this->secret_key = getenv('JWT_SECRET_KEY');
        if (empty($this->secret_key)) {
            throw new Exception('JWT_SECRET_KEY not set in the environment variables.');
        }
    }
    

    public function encode($data) {
        try {
            // Log the data being encoded
            error_log('Encoding data: ' . json_encode($data));
            $data['exp'] = time() + 86400; // Token expires in 1 day
            return JWT::encode($data, $this->secret_key, 'HS256');
        } catch (Exception $e) {
            error_log('JWT encoding failed: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be caught in register.php
        }
    }

    public function decode($jwt) {
        try {
            return (array) JWT::decode($jwt, $this->secret_key, ['HS256']);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
?>