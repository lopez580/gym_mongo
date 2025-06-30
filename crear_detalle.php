<?php
require 'vendor/autoload.php';

use MongoDB\Client;

if (!isset($_GET['id_entrenamiento'])) {
    die("ID de entrenamiento no especificado.");
}

$id_entrenamiento = $_GET['id_entrenamiento'];

$client = new Client("mongodb://mongodb:27017"); // Ajusta host/puerto si necesario
$db = $client->gimnasio;
$ejerciciosColl = $db->ejercicios;

// Obtener ejercicios ordenados por nombre
$ejercicios = $ejerciciosColl->find([], ['sort' => ['nombre' => 1]]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Agregar ejercicio a la sesión</title>
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
        }
        .btn-back:hover {
            background-color: #616f71;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Agregar ejercicio a la sesión</h2>

        <form action="insertar_detalle.php" method="POST">
            <input type="hidden" name="id_entrenamiento" value="<?= htmlspecialchars($id_entrenamiento) ?>">

            <label for="id_ejercicio">Ejercicio:</label>
            <select name="id_ejercicio" id="id_ejercicio" required>
                <option value="">-- Selecciona un ejercicio --</option>
                <?php foreach ($ejercicios as $ejercicio): ?>
                    <option value="<?= $ejercicio['_id'] ?>">
                        <?= htmlspecialchars($ejercicio['nombre']) ?> (<?= htmlspecialchars($ejercicio['grupo_muscular'] ?? '') ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="series">Series:</label>
            <input type="number" name="series" id="series" min="1" required>

            <label for="repeticiones">Repeticiones:</label>
            <input type="number" name="repeticiones" id="repeticiones" min="1" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" step="0.01" name="peso" id="peso" min="0" required>

            <input type="submit" value="Agregar ejercicio">
        </form>

        <a href="listar_detalle.php?id_entrenamiento=<?= htmlspecialchars($id_entrenamiento) ?>" class="btn-back">⬅️ Volver al detalle de la sesión</a>
    </div>
</body>
</html>

