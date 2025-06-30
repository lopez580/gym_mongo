<?php
require 'mongo_db.php';

use MongoDB\BSON\ObjectId;

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = $_GET['id'];

$entrenamientosCol = $client->gimnasio->entrenamientos;


try {
    // Convertir id a ObjectId si usas ObjectId como _id
    $objId = new ObjectId($id);

    $deleteResult = $entrenamientosCol->deleteOne(['_id' => $objId]);

    if ($deleteResult->getDeletedCount() === 1) {
        header("Location: listar_entrenamientos.php");
        exit;
    } else {
        echo "No se encontró el entrenamiento para eliminar.";
    }
} catch (Exception $e) {
    echo "Error al eliminar: " . $e->getMessage();
}
