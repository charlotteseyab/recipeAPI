<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/Database.php';
include_once __DIR__ . '/../core/jwt.php';
include_once __DIR__ . '/../models/Recipe.php';
include_once __DIR__ . '/../models/User.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

error_log('JWT_SECRET_KEY: ' . getenv('JWT_SECRET_KEY'));

$database = new Database();
$db = $database->connect();

