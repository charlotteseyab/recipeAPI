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
    echo json_encode9(['message' => 'Invalid token']);
    exit;
}

$data = json_decodef(file_get_contents("php://input"));
$recipe = new Recipe($db);
$recipe->title = $data->title;
$recipe->description = $data->description;
$recipe->ingredients = $data->ingredients;
$recipe->created_by = $data->created_by;

if($recipe->create()) {
    echo json_encode(['message' => 'Recipe created']);
} else {
    echo json_encode(['message' => 'Failed to create recipe']);
}