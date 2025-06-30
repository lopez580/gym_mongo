<?php
require 'vendor/autoload.php'; // carga la librería MongoDB

$client = new MongoDB\Client("mongodb://mongodb:27017");

$db = $client->gym;
$entrenamientos = $db->entrenamientos;

$result = $entrenamientos->insertOne([
    'fecha' => new MongoDB\BSON\UTCDateTime(),
    'observaciones' => 'Primera sesión creada automáticamente'
]);

echo "Base y colección creadas con el documento ID: " . $result->getInsertedId();
