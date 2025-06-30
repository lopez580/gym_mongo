<?php
require 'mongo_db.php';// tu archivo con conexión MongoDB y $client

use MongoDB\BSON\ObjectId;

if (!isset($_GET['id']) || !isset($_GET['id_entrenamiento'])) {
    die("Parámetros no especificados.");
}

$id_detalle = $_GET['id'];
$id_entrenamiento = $_GET['id_entrenamiento'];

$collectionDetalles = $client->gimnasio->detalle_entrenamiento;
$collectionEjercicios = $client->gimnasio->ejercicios;

// Obtener detalle actual
$detalle = $collectionDetalles->findOne(['_id' => new ObjectId($id_detalle)]);
if (!$detalle) {
    die("Detalle no encontrado.");
}

// Obtener ejercicios para select
$ejerciciosCursor = $collectionEjercicios->find([], ['sort' => ['nombre' => 1]]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar detalle de ejercicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f8;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="number"],
        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input[type="submit"],
        .btn-back {
            display: inline-block;
            padding: 10px 18px;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s ease;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .btn-back {
            background-color: #7f8c8d;
            color: white;
            margin-top: 10px;
            display: inline-block;
        }
        .btn-back:hover {
            background-color: #616f71;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar detalle de ejercicio</h2>

        <form action="actualizar_detalle.php" method="POST">
            <input type="hidden" name="id_detalle" value="<?= htmlspecialchars($detalle['_id']) ?>">
            <input type="hidden" name="id_entrenamiento" value="<?= htmlspecialchars($id_entrenamiento) ?>">

            <label for="id_ejercicio">Ejercicio:</label>
            <select name="id_ejercicio" id="id_ejercicio" required>
                <option value="">-- Selecciona un ejercicio --</option>
                <?php foreach ($ejerciciosCursor as $ejercicio): ?>
                    <option value="<?= $ejercicio['_id'] ?>" <?= ((string)$ejercicio['_id'] === (string)$detalle['id_ejercicio']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ejercicio['nombre']) ?> (<?= htmlspecialchars($ejercicio['grupo_muscular']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="series">Series:</label>
            <input type="number" name="series" id="series" min="1" value="<?= htmlspecialchars($detalle['series']) ?>" required>

            <label for="repeticiones">Repeticiones:</label>
            <input type="number" name="repeticiones" id="repeticiones" min="1" value="<?= htmlspecialchars($detalle['repeticiones']) ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" step="0.01" name="peso" id="peso" min="0" value="<?= htmlspecialchars($detalle['peso_usado']) ?>" required>

            <input type="submit" value="Guardar cambios">
        </form>

        <a href="listar_detalle.php?id_entrenamiento=<?= htmlspecialchars($id_entrenamiento) ?>" class="btn-back">⬅️ Volver al detalle de la sesión</a>
    </div>
</body>
</html>
