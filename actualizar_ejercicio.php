<?php
require 'vendor/autoload.php';
use MongoDB\Client;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no válido.");
}

$client = new Client("mongodb://mongodb:27017");
$db = $client->gimnasio;
$ejercicios = $db->ejercicios;

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$grupo_muscular = $_POST['grupo_muscular'] ?? null;

if (!$id || !$nombre) {
    die("Datos incompletos.");
}

$updateResult = $ejercicios->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => ['nombre' => $nombre, 'grupo_muscular' => $grupo_muscular]]
);

if ($updateResult->getModifiedCount() === 1) {
    header("Location: listar_ejercicios.php");
    exit;
} else {
    echo "Error al actualizar o no hubo cambios.";
}
