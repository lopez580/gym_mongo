<?php
require 'vendor/autoload.php';
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no válido.");
}

try {
    // 1. Conexión correcta a MongoDB en Docker
    $client = new Client("mongodb://mongodb:27017");
    $db = $client->gimnasio;
    $entrenamientos = $db->entrenamientos;

    // 2. Validación de datos
    $id = trim($_POST['id'] ?? '');
    $fecha = $_POST['fecha'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';

    if (empty($id) || empty($fecha)) {
        throw new Exception("Se requieren ID y fecha para la actualización");
    }

    // 3. Validación de formato de ID
    if (!preg_match('/^[a-f\d]{24}$/i', $id)) {
        throw new Exception("Formato de ID inválido: " . htmlspecialchars($id));
    }

    // 4. Conversión del ID
    try {
        $objectId = new ObjectId($id);
    } catch (Exception $e) {
        throw new Exception("ID no válido: " . $e->getMessage());
    }

    // 5. Conversión de fecha
    $fechaObj = new UTCDateTime(strtotime($fecha) * 1000);

    // 6. Operación de actualización
    $updateResult = $entrenamientos->updateOne(
        ['_id' => $objectId],
        ['$set' => [
            'fecha' => $fechaObj,
            'observaciones' => $observaciones
        ]]
    );

    // 7. Manejo de resultados
    if ($updateResult->getMatchedCount() === 0) {
        $document = $entrenamientos->findOne(['_id' => $objectId]);
        if ($document === null) {
            throw new Exception("Documento con ID {$id} no existe en la base de datos");
        } else {
            throw new Exception("Documento existe pero no se pudo actualizar");
        }
    }

    // 8. Redirección exitosa
    header("Location: listar_entrenamientos.php?success=1");
    exit;

} catch (Exception $e) {
    die("Error al actualizar: " . $e->getMessage() .
        "<br><a href='listar_entrenamientos.php'>Volver a la lista</a>");
}
