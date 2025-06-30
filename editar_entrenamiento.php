<?php
require 'mongo_db.php'; // conecta a MongoDB
use MongoDB\BSON\ObjectId;

if (!isset($_GET['id'])) {
    die("ID no especificado");
}

$id = $_GET['id'];

try {
    $objId = new ObjectId($id);
    $entrenamientos = $client->gimnasio->entrenamientos;

    $sesion = $entrenamientos->findOne(['_id' => $objId]);

    if (!$sesion) {
        die("Sesión no encontrada");
    }

    $fecha = $sesion['fecha']->toDateTime()->format('Y-m-d');

} catch (Exception $e) {
    die("ID inválido");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar sesión</title>
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
        form input[type="date"],
        form textarea {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            resize: vertical;
            transition: border-color 0.3s ease;
        }
        form input[type="date"]:focus,
        form textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        form input[type="submit"] {
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
        form input[type="submit"]:hover {
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
        <h2>Editar sesión de entrenamiento</h2>

        <form action="actualizar_entrenamiento.php" method="POST">
            <input type="hidden" name="id" value="<?= $sesion['_id'] ?>">

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?= $fecha ?>" required>

            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" id="observaciones" rows="4"><?= htmlspecialchars($sesion['observaciones'] ?? '') ?></textarea>

            <input type="submit" value="Guardar cambios">
        </form>

        <a href="listar_entrenamientos.php" class="btn-back">⬅️ Volver</a>
    </div>
</body>
</html>
