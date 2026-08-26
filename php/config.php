<?php
// php/config.php
// CONFIGURACIÓN DE LA BASE DE DATOS
$host = 'localhost';
$dbname = 'registro_civil';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// INICIAR SESIÓN PARA EL ADMIN
session_start();

// FUNCIÓN PARA VERIFICAR SI EL ADMIN ESTÁ LOGUEADO
function estaLogueado() {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_rol'] === 'admin';
}

// FUNCIÓN PARA REDIRIGIR SI NO ESTÁ LOGUEADO
function requiereLogin() {
    if (!estaLogueado()) {
        header('Location: login.php');
        exit;
    }
}

// FUNCIÓN PARA OBTENER CONFIGURACIÓN
function getConfig($clave) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT valor FROM configuracion WHERE clave = ?");
    $stmt->execute([$clave]);
    $result = $stmt->fetch();
    return $result ? $result['valor'] : '';
}
?>