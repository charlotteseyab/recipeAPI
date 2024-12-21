<?php
class Recipe {
    private $conn;
    private $table = 'recipes';

    public $id;
    public $title;
    public $description;
    public $ingredients;
    public $created_by;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO " . $htis->table . "SET title = :title, description :description, ingredients :ingredients, created_by :created_by";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':ingredients', $this->ingredients);
        $stmt->bindParam(':created_by', $this->title);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}