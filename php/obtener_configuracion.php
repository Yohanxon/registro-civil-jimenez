<?php
// php/obtener_configuracion.php
require_once 'config.php';

$stmt = $pdo->query("SELECT clave, valor FROM configuracion");
$config = [];

while ($row = $stmt->fetch()) {
    $config[$row['clave']] = $row['valor'];
}

header('Content-Type: application/json');
echo json_encode($config);
?>