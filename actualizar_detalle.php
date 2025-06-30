<?php
require 'vendor/autoload.php';
use MongoDB\Client;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no válido.");
}

$client = new Client("mongodb://mongodb:27017");
$db = $client->gimnasio;
$detalles = $db->detalle_entrenamiento;

$id_detalle = $_POST['id_detalle'] ?? null;
$id_entrenamiento = $_POST['id_entrenamiento'] ?? null;
$id_ejercicio = $_POST['id_ejercicio'] ?? null;
$series = $_POST['series'] ?? null;
$repeticiones = $_POST['repeticiones'] ?? null;
$peso = $_POST['peso'] ?? null;

if (!$id_detalle || !$id_entrenamiento || !$id_ejercicio || !$series || !$repeticiones || $peso === null) {
    die("Datos incompletos.");
}

$updateResult = $detalles->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id_detalle)],
    ['$set' => [
        'id_entrenamiento' => new MongoDB\BSON\ObjectId($id_entrenamiento),
        'id_ejercicio' => new MongoDB\BSON\ObjectId($id_ejercicio),
        'series' => (int)$series,
        'repeticiones' => (int)$repeticiones,
        'peso_usado' => (float)$peso
    ]]
);

if ($updateResult->getModifiedCount() === 1) {
    header("Location: listar_detalle.php?id_entrenamiento=$id_entrenamiento");
    exit;
} else {
    echo "Error al actualizar detalle o no hubo cambios.";
}
