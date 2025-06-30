<?php
require 'mongo_db.php'; // Asegúrate de que mongo_db.php conecta bien a MongoDB

use MongoDB\BSON\ObjectId;

if (!isset($_GET['id_entrenamiento'])) {
    die("ID de entrenamiento no especificado.");
}

$id_entrenamiento = $_GET['id_entrenamiento'];

try {
    $entrenamiento_oid = new ObjectId($id_entrenamiento);
} catch (Exception $e) {
    die("ID de entrenamiento inválido.");
}

// Obtener la sesión
$entrenamientosCol = $client->gimnasio->entrenamientos;
$sesion = $entrenamientosCol->findOne(['_id' => $entrenamiento_oid]);

if (!$sesion) {
    die("Sesión no encontrada.");
}

// Obtener los detalles de la sesión
$detalleCol = $client->gimnasio->detalle_entrenamiento;
$ejerciciosCol = $client->gimnasio->ejercicios;

$detalles = $detalleCol->find(['id_entrenamiento' => $entrenamiento_oid]);

// Crear mapa de ejercicios
$ejercicios_map = [];
foreach ($ejerciciosCol->find() as $ej) {
    $ejercicios_map[(string)$ej['_id']] = $ej;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Detalle de la sesión</title>
    <style>
        /* Tu CSS que ya me pasaste (lo puedes dejar igual) */
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f8;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
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
        .observaciones {
            font-style: italic;
            color: #555;
            margin-bottom: 20px;
        }
        a.btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 10px 10px 20px 0;
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
        .btn-back {
            background-color: #7f8c8d;
            color: white;
        }
        .btn-back:hover {
            background-color: #616f71;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
       table th, table td {
    padding: 12px;
    border: 1px solid #ccc;
    text-align: center;
}

table th {
    background-color: #ecf0f1;
    color: #2c3e50;
}

/* Haz más ancho el recuadro de acciones */
.action-links {
    min-width: 180px; /* Puedes ajustar a más si lo quieres más ancho */
}

.action-links a {
    margin: 0 5px;
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 4px;
    color: white;
    font-size: 14px;
    display: inline-block; /* Para que respeten bien el ancho con el padding */
}

        .edit-link {
            background-color: #f39c12;
        }
        .edit-link:hover {
            background-color: #d35400;
        }
        .delete-link {
            background-color: #e74c3c;
        }
        .delete-link:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ejercicios de la sesión del <?= htmlspecialchars($sesion['fecha']) ?></h2>
        <p class="observaciones">Observaciones: <?= nl2br(htmlspecialchars($sesion['observaciones'] ?? '')) ?></p>

        <a href="crear_detalle.php?id_entrenamiento=<?= $id_entrenamiento ?>" class="btn btn-primary">➕ Agregar ejercicio</a>
        <a href="listar_entrenamientos.php" class="btn btn-back">⬅️ Volver a sesiones</a>

        <table>
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>Ejercicio</th>
                    <th>Grupo Muscular</th>
                    <th>Series</th>
                    <th>Repeticiones</th>
                    <th>Peso (kg)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $hayDetalles = false;
                foreach ($detalles as $detalle) {
                    $hayDetalles = true;
                    $id_detalle = (string)$detalle['_id'];
                    $id_ejercicio = (string)$detalle['id_ejercicio'];
                    $ejercicio = $ejercicios_map[$id_ejercicio] ?? ['nombre' => 'Ejercicio no encontrado', 'grupo_muscular' => ''];

                    echo "<tr>
                        <td>$id_detalle</td>
                        <td>" . htmlspecialchars($ejercicio['nombre']) . "</td>
                        <td>" . htmlspecialchars($ejercicio['grupo_muscular']) . "</td>
                        <td>" . htmlspecialchars($detalle['series']) . "</td>
                        <td>" . htmlspecialchars($detalle['repeticiones']) . "</td>
                        <td>" . htmlspecialchars($detalle['peso_usado']) . "</td>
                        <td class='action-links'>
                            <a href='editar_detalle.php?id=$id_detalle&id_entrenamiento=$id_entrenamiento' class='edit-link'>✏️ Editar</a>
                            <a href='eliminar_detalle.php?id=$id_detalle&id_entrenamiento=$id_entrenamiento' class='delete-link' onclick='return confirm(\"¿Eliminar este ejercicio de la sesión?\")'>🗑️ Eliminar</a>
                        </td>
                    </tr>";
                }
                if (!$hayDetalles) {
                    echo "<tr><td colspan='7' style='padding: 20px; color: #777;'>No hay ejercicios agregados aún.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
