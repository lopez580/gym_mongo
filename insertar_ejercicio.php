<?php
require 'mongo_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $grupo_muscular = $_POST['grupo_muscular'] ?? '';

    // Validar campos si quieres
    if (!$nombre) {
        die("El nombre es obligatorio.");
    }

    $ejerciciosCol = $client->gimnasio->ejercicios;

    $insertResult = $ejerciciosCol->insertOne([
        'nombre' => $nombre,
        'grupo_muscular' => $grupo_muscular
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        header("Location: listar_ejercicios.php");
        exit;
    } else {
        echo "Error al guardar el ejercicio.";
    }
} else {
    echo "Acceso no válido.";
}
