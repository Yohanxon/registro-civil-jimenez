<?php
// admin_citas.php
require_once 'php/config.php';
requiereLogin();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = $_POST['id'] ?? 0;
    
    if ($accion === 'eliminar') {
        $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = '✅ Cita eliminada correctamente';
    } elseif ($accion === 'cambiar_estado') {
        $estado = $_POST['estado'] ?? 'pendiente';
        $stmt = $pdo->prepare("UPDATE citas SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
        $mensaje = '✅ Estado actualizado correctamente';
    }
}

// Obtener todas las citas
$citas = $pdo->query("SELECT * FROM citas ORDER BY fecha DESC, hora DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Citas - Admin</title>
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
                <a href="admin_dashboard.php"><i class="fas fa-chart-pie"></i> Dashboard</a>
                <a href="index.html"><i class="fas fa-home"></i> Inicio</a>
                <a href="admin_logout.php" style="background: #dc3545; padding: 8px 20px; border-radius: 25px;">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </nav>
        </div>
    </header>

    <section class="admin-panel">
        <div class="container">
            <div class="admin-header">
                <h2>
                    <i class="fas fa-calendar-check" style="color: #ffd700;"></i> 
                    Gestión de Citas
                    <span style="font-size: 0.9rem; color: #666; font-weight: normal;">
                        (<?php echo count($citas); ?> citas totales)
                    </span>
                </h2>
            </div>

            <?php if (isset($mensaje)): ?>
                <div class="alert-success"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.06); overflow-x: auto;">
                <?php if (empty($citas)): ?>
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                        <p>No hay citas registradas aún</p>
                    </div>
                <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Tipo Acta</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><strong>#<?php echo $cita['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($cita['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($cita['email']); ?></td>
                            <td><?php echo htmlspecialchars($cita['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($cita['tipo_acta']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></td>
                            <td><?php echo date('H:i', strtotime($cita['hora'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                                    <input type="hidden" name="accion" value="cambiar_estado">
                                    <select name="estado" onchange="this.form.submit()" 
                                        style="padding: 5px 10px; border-radius: 5px; border: 2px solid #e0e0e0; background: white; cursor: pointer;">
                                        <option value="pendiente" <?php echo $cita['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="confirmada" <?php echo $cita['estado'] == 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                                        <option value="cancelada" <?php echo $cita['estado'] == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?')">
                                    <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>