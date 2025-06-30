<?php
require 'vendor/autoload.php'; // Autoload de Composer para MongoDB

$uri = "mongodb://gym_mongo_db:27017"; // nombre del servicio de mongo en docker-compose
$options = [];

try {
    $client = new MongoDB\Client($uri, $options);
} catch (Exception $e) {
    die("Error de conexión a MongoDB: " . $e->getMessage());
}
