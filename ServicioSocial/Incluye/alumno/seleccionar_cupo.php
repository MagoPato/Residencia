<?php
session_start();
include '../../Modulos/conexion.php';
include '../../Seguridad/control_sesion.php';

if (!isset($_SESSION['no_control'])) {
    echo "<div class='alert alert-warning'>No hay una sesión activa o el usuario no está identificado.</div>";
    exit;
}

$usuario_id = $_SESSION['no_control'];
$id_servicio = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_servicio'])) {
    $id_servicio = intval($_POST['id_servicio']);
} elseif (isset($_GET['id_servicio'])) {
    $id_servicio = intval($_GET['id_servicio']);
}

if ($id_servicio <= 0) {
    echo "<div class='alert alert-danger text-center'>No se proporcionó un servicio válido para mostrar.</div>";
    exit;
}

// Obtener datos del alumno
$alumno_stmt = $enlace->prepare("SELECT * FROM alumno WHERE No_Control = ?");
$alumno_stmt->bind_param("s", $usuario_id);
$alumno_stmt->execute();
$alumno_result = $alumno_stmt->get_result();
$alumno_row = $alumno_result->fetch_assoc();
$id_direccion = $alumno_row['Id_Direccion'];

// Dirección
$direccion_stmt = $enlace->prepare("SELECT * FROM direccion WHERE Id = ?");
$direccion_stmt->bind_param("i", $id_direccion);
$direccion_stmt->execute();
$direccion_result = $direccion_stmt->get_result();
$direccion_row = $direccion_result->fetch_assoc();

// Servicio
$servicio_stmt = $enlace->prepare("SELECT 
    d.Nombre AS dependencia, d.Encargado_N, d.Encargado_A, 
    s.Programa, s.Departamento, s.Actividades, s.Id_Dependencia, s.Id_Responsable,
    r.nombre AS responsable_nombre, r.puesto AS responsable_puesto,
    c.Id AS cupo_id, c.Cupo, c.Carrera, c.Horario
FROM servicio s
JOIN dependencia d ON s.Id_Dependencia = d.Id
JOIN responsable r ON s.Id_Responsable = r.id
LEFT JOIN cupos c ON s.Id = c.Id_Servicio
WHERE s.Id = ?");
$servicio_stmt->bind_param("i", $id_servicio);
$servicio_stmt->execute();
$servicio_result = $servicio_stmt->get_result();
$servicio_row = $servicio_result->fetch_assoc();

if (!$servicio_row) {
    echo "<div class='alert alert-danger text-center'>No se encontró información del servicio.</div>";
    exit;
}

// Variables para controlar el flujo
$inscripcion_exitosa = false;
$error_message = '';

// Procesar inscripción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscribir_servicio'])) {
    $enlace->begin_transaction();

    try {
        // Verificar si ya está inscrito
        $check_stmt = $enlace->prepare("SELECT COUNT(*) as count FROM inscripciones WHERE Id_Alumno = ? AND Id_Servicio = ?");
        $check_stmt->bind_param("si", $usuario_id, $id_servicio);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_row = $check_result->fetch_assoc();
        
        if ($check_row['count'] > 0) {
            throw new Exception("Ya estás inscrito en este servicio.");
        }

        // Verificar cupos disponibles
        if ($servicio_row['Cupo'] <= 0) {
            throw new Exception("No hay cupos disponibles para este servicio.");
        }

        $stmt = $enlace->prepare("INSERT INTO inscripciones (Id_Alumno, Id_Cupo, Id_Servicio, Id_Dependencia, Id_Rspnb) 
                                  VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiii", 
            $usuario_id, 
            $servicio_row['cupo_id'], 
            $id_servicio, 
            $servicio_row['Id_Dependencia'], 
            $servicio_row['Id_Responsable']
        );
        if (!$stmt->execute()) {
            throw new Exception("Error al registrar inscripción.");
        }

        $update_cupo = $enlace->prepare("UPDATE cupos SET Cupo = Cupo - 1 WHERE Id = ? AND Cupo > 0");
        $update_cupo->bind_param("i", $servicio_row['cupo_id']);
        if (!$update_cupo->execute() || $update_cupo->affected_rows === 0) {
            throw new Exception("No hay cupos disponibles o error al actualizar.");
        }

        $enlace->commit();
        $inscripcion_exitosa = true;

    } catch (Exception $e) {
        $enlace->rollback();
        $error_message = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Servicio Social - Alumno</title>
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <!-- Iconos -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>    
</head>
<body class="sb-nav-fixed">
        
    <?php include 'barra.php'; ?>
         <div id="layoutSidenav_content">
    <main class="container mt-5">
        <?php if (!empty($error_message)): ?>
            <div class='alert alert-danger'><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <?php if ($inscripcion_exitosa): ?>
            <div class='alert alert-success text-center'>
                <h4>¡Inscripción Exitosa!</h4>
                <p>Tu inscripción se ha registrado correctamente.</p>
                <a href="IndexAlum.php" class="btn btn-success">Ir al Panel Principal</a>
            </div>
        <?php else: ?>
            <h5 class="text-center">Detalles del Alumno</h5>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($alumno_row['Nombre'] . ' ' . $alumno_row['Apellido_P'] . ' ' . $alumno_row['Apellido_M']) ?></h5>
                    <p><strong>No. Control:</strong> <?= htmlspecialchars($alumno_row['No_Control']) ?></p>
                    <p><strong>Carrera:</strong> <?= htmlspecialchars($alumno_row['Carrera']) ?></p>
                    <p><strong>Correo:</strong> <?= htmlspecialchars($alumno_row['Correo']) ?></p>
                    <p><strong>Semestre:</strong> <?= htmlspecialchars($alumno_row['Semestre']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($alumno_row['Telefono']) ?></p>
                </div>
            </div>

            <h5 class="text-center">Datos del Servicio Seleccionado - ID del Cupo: <?= htmlspecialchars($servicio_row['cupo_id']) ?></h5>
            <div class="card shadow">
                <div class="card-body">
                    <p><strong>Programa:</strong> <?= htmlspecialchars($servicio_row['Programa']) ?></p>
                    <p><strong>Encargado:</strong> <?= htmlspecialchars($servicio_row['Encargado_N'] . ' ' . $servicio_row['Encargado_A']) ?></p>
                    <p><strong>Actividades:</strong> <?= nl2br(htmlspecialchars($servicio_row['Actividades'])) ?></p>
                    <p><strong>Horario:</strong> <?= htmlspecialchars($servicio_row['Horario']) ?></p>
                    <p><strong>Carrera Admitida:</strong> <?= htmlspecialchars($servicio_row['Carrera']) ?></p>
                    <p><strong>Cupos Disponibles:</strong> 
                        <span class="badge <?= ($servicio_row['Cupo'] > 0) ? 'badge-success' : 'badge-danger' ?>">
                            <?= htmlspecialchars($servicio_row['Cupo']) ?>
                        </span>
                    </p>
                </div>
            </div>

            <form method="post" id="formInscripcion">
                <input type="hidden" name="inscribir_servicio" value="1">
                <input type="hidden" name="id_servicio" value="<?= $id_servicio ?>">
                <button type="submit" class="btn btn-primary mt-3" 
                        <?= ($servicio_row['Cupo'] <= 0) ? 'disabled' : '' ?>>
                    <?= ($servicio_row['Cupo'] <= 0) ? 'Sin Cupos Disponibles' : 'Inscribirse al Servicio' ?>
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="IndexAlum.php" class="btn btn-secondary">Volver al Panel Principal</a>
            </div>
        <?php endif; ?>
    </main>
        <br>
 <?php
        // Incluyendo pie de página
        include '../../Modulos/footer.php';
        ?>
        
    <script>
    document.getElementById('formInscripcion')?.addEventListener('submit', function(e) {
        // Mostrar loading
        const button = this.querySelector('button[type="submit"]');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Procesando...';
        }
    });
    </script>
                 </div>
</body>
</html>