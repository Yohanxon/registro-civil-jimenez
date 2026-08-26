<?php
// admin_dashboard.php
require_once 'php/config.php';
requiereLogin();

// Obtener estadísticas
$total_citas = $pdo->query("SELECT COUNT(*) FROM citas")->fetchColumn();
$pendientes = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'pendiente'")->fetchColumn();
$confirmadas = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'confirmada'")->fetchColumn();
$canceladas = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'cancelada'")->fetchColumn();
$total_requisitos = $pdo->query("SELECT COUNT(*) FROM requisitos")->fetchColumn();

// Obtener últimas citas
$ultimas_citas = $pdo->query("SELECT * FROM citas ORDER BY id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Registro Civil</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="admin_logout.php" style="background: #dc3545; padding: 8px 20px; border-radius: 25px;">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </nav>
        </div>
    </header>

    <section class="admin-panel">
        <div class="container">
            <div class="admin-header">
                <div>
                    <h2 style="color: #0a1628;">
                        <i class="fas fa-user-shield" style="color: #ffd700;"></i> 
                        Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?>
                    </h2>
                    <p style="color: #666; margin-top: 5px;">
                        <i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y H:i'); ?>
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="admin_citas.php" class="btn-add">
                        <i class="fas fa-calendar-check"></i> Gestionar Citas
                    </a>
                    <a href="admin_requisitos.php" class="btn-add" style="background: #0a1628; color: white;">
                        <i class="fas fa-list"></i> Gestionar Requisitos
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.06); border-top: 4px solid #0a1628;">
                    <div style="font-size: 2.5rem; color: #0a1628;"><?php echo $total_citas; ?></div>
                    <p style="color: #666; font-weight: 500;">Total Citas</p>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.06); border-top: 4px solid #ffc107;">
                    <div style="font-size: 2.5rem; color: #ffc107;"><?php echo $pendientes; ?></div>
                    <p style="color: #666; font-weight: 500;">Pendientes</p>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.06); border-top: 4px solid #28a745;">
                    <div style="font-size: 2.5rem; color: #28a745;"><?php echo $confirmadas; ?></div>
                    <p style="color: #666; font-weight: 500;">Confirmadas</p>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.06); border-top: 4px solid #dc3545;">
                    <div style="font-size: 2.5rem; color: #dc3545;"><?php echo $canceladas; ?></div>
                    <p style="color: #666; font-weight: 500;">Canceladas</p>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.06); border-top: 4px solid #ffd700;">
                    <div style="font-size: 2.5rem; color: #ffd700;"><?php echo $total_requisitos; ?></div>
                    <p style="color: #666; font-weight: 500;">Requisitos</p>
                </div>
            </div>

            <!-- Últimas citas -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.06);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #0a1628;">
                        <i class="fas fa-clock" style="color: #ffd700;"></i> Últimas Citas
                    </h3>
                    <a href="admin_citas.php" style="color: #0a1628; font-weight: 600;">
                        Ver todas <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimas_citas as $cita): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cita['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($cita['tipo_acta']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha'] . ' ' . $cita['hora'])); ?></td>
                            <td>
                                <span class="status-<?php echo $cita['estado']; ?>">
                                    <?php echo ucfirst($cita['estado']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ultimas_citas)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999; padding: 30px;">
                                <i class="fas fa-inbox"></i> No hay citas registradas
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>