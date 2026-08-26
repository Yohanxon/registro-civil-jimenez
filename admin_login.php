<?php
// admin_login.php
require_once 'php/config.php';

// Si ya está logueado, redirigir
if (estaLogueado()) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';

// CREDENCIALES CORRECTAS (HARDCODEADAS PARA ASEGURAR FUNCIONAMIENTO)
$ADMIN_EMAIL = 'admin@registrocivil.com';
$ADMIN_PASSWORD = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = '⚠️ Por favor, ingresa email y contraseña';
    } else {
        // Verificar en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND rol = 'admin'");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        
        // Si existe en la BD, verificar contraseña
        if ($admin) {
            // Verificar con password_verify
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nombre'] = $admin['nombre'];
                $_SESSION['admin_rol'] = $admin['rol'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = '❌ Contraseña incorrecta';
            }
        // VERIFICACIÓN SIMPLE (TEMPORAL)
if ($email === 'admin@registrocivil.com' && $password === 'admin123') {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_nombre'] = 'Administrador';
    $_SESSION['admin_rol'] = 'admin';
    header('Location: admin_dashboard.php');
    exit;
}
        } else {
            // Si no existe, crear el admin automáticamente
            try {
                $hashed = password_hash($ADMIN_PASSWORD, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'admin')");
                $stmt->execute(['Administrador', $ADMIN_EMAIL, $hashed]);
                
                // Iniciar sesión automáticamente
                $_SESSION['admin_id'] = $pdo->lastInsertId();
                $_SESSION['admin_nombre'] = 'Administrador';
                $_SESSION['admin_rol'] = 'admin';
                header('Location: admin_dashboard.php');
                exit;
            } catch (PDOException $e) {
                $error = '❌ Error al crear el administrador: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Registro Civil</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            text-align: center;
            color: #0a1628;
            margin-bottom: 10px;
        }
        .login-container .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .login-logo {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .credenciales-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
            border-left: 4px solid #ffd700;
        }
        .credenciales-info strong {
            color: #0a1628;
        }
        .btn-login {
            background: #ffd700;
            color: #0a1628;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #e6c200;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 215, 0, 0.3);
        }
        .btn-login i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>REGISTRO CIVIL MUNICIPAL DE JIMÉNEZ</h1>
                <p class="slogan">✦ Panel de Administración ✦</p>
            </div>
            <nav>
                <a href="index.html"><i class="fas fa-home"></i> Inicio</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="login-container">
            <div class="login-logo">🔐</div>
            <h2>Acceso Administrador</h2>
            <p class="subtitle">Ingresa tus credenciales para gestionar el sistema</p>
            
            <?php if ($error): ?>
                <div class="alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" placeholder="admin@registrocivil.com" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" name="password" placeholder="admin123" required>
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>
            </form>
            
            <div class="credenciales-info">
                <p><strong>📋 Credenciales de prueba:</strong></p>
                <p>👤 Usuario: <strong>admin@registrocivil.com</strong></p>
                <p>🔑 Contraseña: <strong>admin123</strong></p>
                <p style="margin-top: 10px; font-size: 0.85rem; color: #999;">
                    <i class="fas fa-info-circle"></i> Si no existe, se creará automáticamente
                </p>
            </div>
        </div>
    </div>
</body>
</html>