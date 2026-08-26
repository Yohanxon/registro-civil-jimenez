<?php
// php/obtener_requisitos.php
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM requisitos ORDER BY id");
$requisitos = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($requisitos);
?>