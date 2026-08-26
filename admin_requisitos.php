<?php
// admin_requisitos.php
require_once 'php/config.php';
requiereLogin();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'agregar') {
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $icono = $_POST['icono'] ?? '📄';
            $stmt = $pdo->prepare("INSERT INTO requisitos (titulo, descripcion, icono) VALUES (?, ?, ?)");
            $stmt->execute([$titulo, $descripcion, $icono]);
            $mensaje = 'Requisito agregado correctamente';
        } elseif ($_POST['accion'] === 'eliminar') {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM requisitos WHERE id = ?");
            $stmt->execute([$id]);
            $mensaje = 'Requisito eliminado correctamente';
        }
    }
}

$requisitos = $pdo->query("SELECT * FROM requisitos ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Requisitos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>REGISTRO CIVIL MUNICIPAL DE JIMÉNEZ</h1>
                <p class="slogan">Panel de Administración</p>
            </div>
            <nav>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="admin_logout.php" style="background: #dc3545; padding: 8px 15px; border-radius: 5px;">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <section class="admin-panel">
        <div class="container">
            <div class="admin-header">
                <h2>Gestión de Requisitos</h2>
                <button class="btn-add" onclick="mostrarFormulario()">+ Agregar Requisito</button>
            </div>

            <?php if (isset($mensaje)): ?>
                <div class="alert-success">✅ <?php echo $mensaje; ?></div>
            <?php endif; ?>

            <!-- Formulario para agregar -->
            <div id="formRequisito" style="display: none; background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <form method="POST">
                    <input type="hidden" name="accion" value="agregar">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label>Icono (emoji)</label>
                            <input type="text" name="icono" placeholder="📄">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit" style="width: auto;">Guardar Requisito</button>
                    <button type="button" onclick="ocultarFormulario()" style="margin-left: 10px; padding: 12px 20px;">Cancelar</button>
                </form>
            </div>

            <!-- Lista de requisitos -->
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 3px 15px rgba(0,0,0,0.08);">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Icono</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requisitos as $req): ?>
                        <tr>
                            <td style="font-size: 2rem;"><?php echo $req['icono'] ?? '📄'; ?></td>
                            <td><?php echo htmlspecialchars($req['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($req['descripcion']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar este requisito?')">
                                    <input type="hidden" name="id" value="<?php echo $req['id']; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        function mostrarFormulario() {
            document.getElementById('formRequisito').style.display = 'block';
        }
        function ocultarFormulario() {
            document.getElementById('formRequisito').style.display = 'none';
        }
    </script>
</body>
</html>