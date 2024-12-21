<?php
class User{
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register(){
        // Check if the email already exists
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Email already exists
            return false; // Indicate that registration failed due to duplicate email
        }

        // Proceed with registration
        $query = "INSERT INTO " . $this->table . " SET name = :name, email = :email, password = :password";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $this->password);

        if (!$stmt->execute()) {
            // Log the error if the execution fails
            error_log('Database registration failed: ' . implode(", ", $stmt->errorInfo()));
            return false; // Indicate that registration failed
        }

        return true; // Indicate that registration was successful
    }

    public function login($email, $password) {
        try {
            // Prepare the SQL statement
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Check if a user was found
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    return true; // Login successful
                }
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
        }
        return false; // Login failed
    }
}