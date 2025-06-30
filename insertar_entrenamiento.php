<?php
require 'mongo_db.php';

use MongoDB\BSON\UTCDateTime;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha_str = $_POST['fecha'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';
    $action = $_POST['action'] ?? '';

    // Validar fecha (debe estar en formato YYYY-MM-DD)
    if (!$fecha_str || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_str)) {
        die("Fecha inválida.");
    }

    // Convertir la fecha a objeto MongoDB UTCDateTime
    // Convertimos la fecha a timestamp en milisegundos
    $fecha_ts = strtotime($fecha_str) * 1000;
    $fecha = new UTCDateTime($fecha_ts);

    $entrenamientosCol = $client->gym->entrenamientos;

    // Insertar nuevo documento
    $insertResult = $entrenamientosCol->insertOne([
        'fecha' => $fecha,
        'observaciones' => $observaciones
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        if ($action === 'guardar_volver') {
            header("Location: listar_entrenamientos.php");
            exit;
        } elseif ($action === 'guardar_nuevo') {
            header("Location: crear_entrenamiento.php");
            exit;
        } else {
            header("Location: listar_entrenamientos.php");
            exit;
        }
    } else {
        echo "❌ Error al guardar el entrenamiento.";
    }
} else {
    echo "Acceso no válido.";
}
