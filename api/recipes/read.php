<?php
include_once '../../core/initialize.php';
include_once '../../includes/headers.php';

$recipe = new Recipe($db);
$recipes = $recipe->readAll(); // Assuming this method fetches all recipes

if($recipes) {
    echo json_encode($recipes);
} else {
    echo json_encode(['message' => 'No recipes found']);
}
