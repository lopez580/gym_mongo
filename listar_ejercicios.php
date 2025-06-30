<?php
require 'mongo_db.php';  // Archivo para conectar con Mongo (te lo preparo abajo)

// Seleccionamos la colección 'ejercicios'
$collection = $client->gimnasio->ejercicios;


// Obtenemos todos los documentos, ordenados por nombre ascendente
$ejercicios = $collection->find([], ['sort' => ['nombre' => 1]]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ejercicios</title>
    <style>
        /* Repite tu mismo CSS aquí o enlázalo */
        body { font-family: Arial, sans-serif; background: #f4f7f8; margin: 20px; color: #333; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 25px; }
        a.btn-primary { display: inline-block; background-color: #3498db; color: white; padding: 10px 18px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-bottom: 20px; transition: background-color 0.3s ease; }
        a.btn-primary:hover { background-color: #2980b9; }
        table { width: 100%; border-collapse: collapse; font-size: 16px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f9fafd; }
        a.btn-edit { color: #f39c12; text-decoration: none; font-weight: 600; margin-right: 10px; transition: color 0.3s ease; }
        a.btn-edit:hover { color: #d35400; }
        a.btn-delete { color: #e74c3c; text-decoration: none; font-weight: 600; transition: color 0.3s ease; }
        a.btn-delete:hover { color: #c0392b; }
        a.btn-back { display: inline-block; margin-top: 25px; color: white; background-color: #7f8c8d; padding: 10px 18px; border-radius: 5px; text-decoration: none; transition: background-color 0.3s ease; }
        a.btn-back:hover { background-color: #616f71; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de ejercicios</h2>
        <a href="crear_ejercicio.php" class="btn-primary">➕ Agregar nuevo ejercicio</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Grupo muscular</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ejercicios as $ejercicio): ?>
                <tr>
                    <td><?= $ejercicio['_id'] ?></td>
                    <td><?= htmlspecialchars($ejercicio['nombre']) ?></td>
                    <td><?= htmlspecialchars($ejercicio['grupo_muscular'] ?? '') ?></td>
                    <td>
                        <a href="editar_ejercicio.php?id=<?= $ejercicio['_id'] ?>" class="btn-edit">✏️ Editar</a>
                        <a href="eliminar_ejercicio.php?id=<?= $ejercicio['_id'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar este ejercicio?')">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="listar_entrenamientos.php" class="btn-back">⬅️ Volver a entrenamientos</a>
    </div>
</body>
</html>
