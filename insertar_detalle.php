<?php
require 'mongo_db.php';

use MongoDB\BSON\ObjectId;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibe los datos del formulario
    $id_entrenamiento = $_POST['id_entrenamiento'] ?? null;
    $id_ejercicio = $_POST['id_ejercicio'] ?? null;
    $series = isset($_POST['series']) ? (int)$_POST['series'] : 0;
    $repeticiones = isset($_POST['repeticiones']) ? (int)$_POST['repeticiones'] : 0;
    $peso = isset($_POST['peso']) ? (float)$_POST['peso'] : 0.0;

    if (!$id_entrenamiento || !$id_ejercicio) {
        die("Faltan datos obligatorios.");
    }

    $detalleCol = $client->gimnasio->detalle_entrenamiento;

    try {
        // Convertir los IDs a ObjectId para que coincidan en la consulta
        $objId_entrenamiento = new ObjectId($id_entrenamiento);
        $objId_ejercicio = new ObjectId($id_ejercicio);
    } catch (Exception $e) {
        die("ID inválido: " . $e->getMessage());
    }

    // Insertar documento con ObjectIds
    $insertResult = $detalleCol->insertOne([
        'id_entrenamiento' => $objId_entrenamiento,
        'id_ejercicio' => $objId_ejercicio,
        'series' => $series,
        'repeticiones' => $repeticiones,
        'peso_usado' => $peso
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        header("Location: listar_detalle.php?id_entrenamiento=" . urlencode($id_entrenamiento));
        exit;
    } else {
        echo "Error al agregar detalle.";
    }
} else {
    echo "Acceso no válido.";
}
