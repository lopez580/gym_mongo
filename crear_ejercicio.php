<?php
require 'vendor/autoload.php';
use MongoDB\Client;

$client = new Client("mongodb://mongodb:27017");
$db = $client->gimnasio;
$ejercicios = $db->ejercicios;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $grupo_muscular = $_POST['grupo_muscular'] ?? '';

    if (!$nombre) {
        die("El nombre del ejercicio es obligatorio.");
    }

    $document = [
        'nombre' => $nombre,
        'grupo_muscular' => $grupo_muscular,
    ];

    $result = $ejercicios->insertOne($document);

    if ($result->getInsertedCount() === 1) {
        header("Location: listar_ejercicios.php");
        exit;
    } else {
        echo "Error al insertar el ejercicio.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Agregar ejercicio</title>
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
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #555;
        }
        form input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        form input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }
        button[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #2980b9;
        }
        a.btn-back {
            display: inline-block;
            margin-top: 20px;
            color: white;
            background-color: #7f8c8d;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        a.btn-back:hover {
            background-color: #616f71;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Agregar nuevo ejercicio</h2>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="grupo_muscular">Grupo muscular:</label>
            <input type="text" id="grupo_muscular" name="grupo_muscular">

            <button type="submit">Guardar ejercicio</button>
        </form>

        <a href="listar_ejercicios.php" class="btn-back">⬅️ Volver a la lista</a>
    </div>
</body>
</html>
