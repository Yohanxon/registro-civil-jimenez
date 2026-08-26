<?php
// php/guardar_cita.php
require_once 'config.php';

// Obtener datos del POST (JSON)
$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos no recibidos']);
    exit;
}

// Validar datos
$nombre = trim($datos['nombre'] ?? '');
$email = trim($datos['email'] ?? '');
$telefono = trim($datos['telefono'] ?? '');
$tipo_acta = trim($datos['tipo_acta'] ?? '');
$fecha = trim($datos['fecha'] ?? '');
$hora = trim($datos['hora'] ?? '');
$notas = trim($datos['notas'] ?? '');

if (empty($nombre) || empty($email) || empty($telefono) || empty($tipo_acta) || empty($fecha) || empty($hora)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios']);
    exit;
}

try {
    // Guardar en la base de datos
    $sql = "INSERT INTO citas (nombre_completo, email, telefono, tipo_acta, fecha, hora, notas) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([$nombre, $email, $telefono, $tipo_acta, $fecha, $hora, $notas]);
    
    if ($resultado) {
        echo json_encode(['exito' => true, 'mensaje' => 'Cita guardada correctamente']);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Error al guardar']);
    }
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>