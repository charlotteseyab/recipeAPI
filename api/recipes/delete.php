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
$recipe = new Recipe($db);
$recipe->id = $recipeId; // Assuming the Recipe class has an id property

if($recipe->delete()) { // Assuming this method deletes the recipe
    echo json_encode(['message' => 'Recipe deleted']);
} else {
    echo json_encode(['message' => 'Failed to delete recipe']);
}
