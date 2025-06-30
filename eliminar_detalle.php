<?php
require 'mongo_db.php';

use MongoDB\BSON\ObjectId;

if (!isset($_GET['id']) || !isset($_GET['id_entrenamiento'])) {
    die("Parámetros no especificados.");
}

$id_detalle = $_GET['id'];
$id_entrenamiento = $_GET['id_entrenamiento'];

$detalleCol = $client->gimnasio->detalle_entrenamiento;

try {
    $objId = new ObjectId($id_detalle);

    $deleteResult = $detalleCol->deleteOne(['_id' => $objId]);

    if ($deleteResult->getDeletedCount() === 1) {
        header("Location: listar_detalle.php?id_entrenamiento=$id_entrenamiento");
        exit;
    } else {
        echo "No se encontró el detalle para eliminar.";
    }
} catch (Exception $e) {
    echo "Error al eliminar detalle: " . $e->getMessage();
}
