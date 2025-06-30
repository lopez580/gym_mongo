<?php
require 'mongo_db.php';

use MongoDB\BSON\ObjectId;

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = $_GET['id'];
$ejerciciosCol = $client->gimnasio->ejercicios;

try {
    $objId = new ObjectId($id);

    $deleteResult = $ejerciciosCol->deleteOne(['_id' => $objId]);

    if ($deleteResult->getDeletedCount() === 1) {
        header("Location: listar_ejercicios.php");
        exit;
    } else {
        echo "No se encontró el ejercicio para eliminar.";
    }
} catch (Exception $e) {
    echo "Error al eliminar: " . $e->getMessage();
}
