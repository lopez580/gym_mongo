<?php
require 'vendor/autoload.php';

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

// Conexión a MongoDB
$client = new Client("mongodb://mongodb:27017");
$db = $client->gimnasio;
$entrenamientos = $db->entrenamientos;

// Procesar formulario cuando es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = $_POST['fecha'] ?? null;
    $observaciones = $_POST['observaciones'] ?? '';
    $action = $_POST['action'] ?? 'guardar_volver';

    if (!$fecha) {
        $error = "La fecha es obligatoria.";
    } else {
        try {
            // Convertir fecha a formato MongoDB
            $fechaMongo = new UTCDateTime(strtotime($fecha) * 1000);

            $result = $entrenamientos->insertOne([
                'fecha' => $fechaMongo,
                'observaciones' => $observaciones
            ]);

            if ($result->getInsertedCount() === 1) {
                // Redirigir según acción
                if ($action === 'guardar_volver') {
                    header("Location: listar_entrenamientos.php");
                    exit;
                } elseif ($action === 'guardar_nuevo') {
                    header("Location: crear_entrenamiento.php?success=1");
                    exit;
                }
            } else {
                $error = "Error al guardar el entrenamiento.";
            }
        } catch (Exception $e) {
            $error = "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Sesión de Entrenamiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f8;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="date"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            padding: 10px 15px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        .error {
            color: #e74c3c;
            padding: 10px;
            background: #fdecea;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success {
            color: #27ae60;
            padding: 10px;
            background: #e8f8f0;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nueva Sesión de Entrenamiento</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="success">¡Sesión creada exitosamente!</div>
        <?php endif; ?>

        <form method="POST">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" required
                   value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>">

            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" id="observaciones"><?= 
                htmlspecialchars($_POST['observaciones'] ?? '') 
            ?></textarea>

            <div class="button-group">
                <button type="submit" name="action" value="guardar_volver" 
                        class="btn btn-primary">
                    Guardar y Volver
                </button>
                <button type="submit" name="action" value="guardar_nuevo" 
                        class="btn btn-secondary">
                    Guardar y Crear Otra
                </button>
            </div>
        </form>
    </div>
</body>
</html>