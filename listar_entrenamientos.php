<?php
require 'mongo_db.php';

// Seleccionamos la colección 'entrenamientos'
$collection = $client->gimnasio->entrenamientos;


// Obtenemos todos los documentos ordenados por fecha descendente
$entrenamientos = $collection->find([], ['sort' => ['fecha' => -1]]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Sesiones de Entrenamiento</title>
    <style>
        /* Aquí va tu CSS igual, para no extender mucho */
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f8;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-edit {
            background-color: #f39c12;
            color: white;
        }
        .btn-edit:hover {
            background-color: #d35400;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .session-card {
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .session-info {
            max-width: 70%;
        }
        .session-date {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .session-observations {
            font-size: 14px;
            color: #666;
        }
        .actions {
            white-space: nowrap;
        }
        .btn-add-session {
            display: block;
            margin: 30px auto 10px auto;
            width: 200px;
            text-align: center;
        }
        .btn-add-exercise {
            display: block;
            margin: 10px auto 30px auto;
            width: 200px;
            text-align: center;
            background-color: #27ae60;
            color: white;
            border-radius: 5px;
            padding: 10px 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-add-exercise:hover {
            background-color: #1e8449;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mis Sesiones de Entrenamiento</h1>

        <a href="crear_entrenamiento.php" class="btn btn-primary btn-add-session">➕ Nueva Sesión</a>
        <a href="listar_ejercicios.php" class="btn-add-exercise">🏋️‍♂️ Lista de Ejercicios</a>

        <?php if ($entrenamientos->isDead()): ?>
            <p>No tienes sesiones registradas aún.</p>
        <?php else: ?>
            <?php foreach ($entrenamientos as $entrenamiento): ?>
                <?php
                    // Convertir fecha MongoDB UTCDateTime a string legible
                    $fecha = $entrenamiento['fecha']->toDateTime()->format('Y-m-d');
                ?>
                <div class="session-card">
                    <div class="session-info">
                        <div class="session-date"><?= htmlspecialchars($fecha) ?></div>
                        <div class="session-observations"><?= nl2br(htmlspecialchars($entrenamiento['observaciones'] ?? '')) ?></div>
                    </div>
                    <div class="actions">
                        <a href="listar_detalle.php?id_entrenamiento=<?= $entrenamiento['_id'] ?>" class="btn btn-primary">Ver detalles</a>
                        <a href="editar_entrenamiento.php?id=<?= $entrenamiento['_id'] ?>" class="btn btn-edit">Editar</a>
                        <a href="eliminar_entrenamiento.php?id=<?= $entrenamiento['_id'] ?>" class="btn btn-delete" onclick="return confirm('¿Eliminar esta sesión?')">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
