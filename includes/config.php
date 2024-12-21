<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('APP_NAME', 'Recipe API');
define('JWT_SECRET', $_ENV['JWT_SECRET']);