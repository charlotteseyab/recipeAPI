<?php
include_once '../../core/initialize.php';
include_once '../../includes/headers.php';

$headers = getallheaders();
if(!isset($headers['Authorization'])) {
    echo json_encode(['message' => 'No token provided']);
    exit;
}

$token = str_replace('Bearer ', '', $headers['Authorization']);
$decoded = $jwtHandler->decode($token);

if($decoded) {
    echo json_encode(['message' => 'Invalid token']);
    exit;
}

if(!isset($_GET['id'])) {
    echo json_encode(['message' => 'Recipe ID not provided']);
    exit;
}

$recipeId = $_GET['id'];
$data = json_decode(file_get_contents("php://input")); // Get the updated data

$recipe = new Recipe($db);
$recipe->id = $recipeId; // Set the recipe ID

// Update only the fields that are provided in the request
if(isset($data->title)) {
    $recipe->title = $data->title;
}
if(isset($data->description)) {
    $recipe->description = $data->description;
}
if(isset($data->ingredients)) {
    $recipe->ingredients = $data->ingredients;
}
if(isset($data->created_by)) {
    $recipe->created_by = $data->created_by;
}

if($recipe->update()) { // Assuming this method updates the recipe
    echo json_encode(['message' => 'Recipe updated']);
} else {
    echo json_encode(['message' => 'Failed to update recipe']);
}
