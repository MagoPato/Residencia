<?php
session_start();  // Inicia la sesión

include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos

include '../../Seguridad/control_sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Detalles del servicio social de alumnos" />
    <meta name="author" content="" />
    <title>Servicio Social - Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">

<?php
if (!isset($_POST['id_servicio'])) {
    echo "<div class='alert alert-danger text-center'>No se proporcionó un servicio válido para mostrar.</div>";
    exit;
}

$id_servicio = intval($_POST['id_servicio']);

$query = "
    SELECT 
        d.Nombre AS dependencia, 
        d.Encargado_N, 
        d.Encargado_A, 
        s.Programa, 
        s.Departamento, 
        s.Actividades, 
        r.Nombre AS responsable_nombre, 
        r.Puesto AS responsable_puesto
    FROM servicio s 
    JOIN dependencia d ON s.Id_Dependencia = d.Id
    JOIN responsable r ON s.Id_Responsable = r.Id
    WHERE s.Id = ?";
$stmt = $enlace->prepare($query);
$stmt->bind_param("i", $id_servicio);

if (!$stmt->execute()) {
    echo "<div class='alert alert-danger text-center'>Error al ejecutar la consulta: " . $stmt->error . "</div>";
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning text-center'>No se encontró información para el servicio seleccionado.</div>";
    exit;
}

$servicio = $result->fetch_assoc();

// CONSULTA CORREGIDA: Solo obtener cupos disponibles (Cupo > 0)
$query_plazas = "
    SELECT c.Id, c.Carrera, c.Horario, c.Cupo 
    FROM cupos c
    WHERE c.Id_Servicio = ? AND c.Cupo > 0
    ORDER BY c.Carrera, c.Horario
";
$stmt_plazas = $enlace->prepare($query_plazas);
$stmt_plazas->bind_param("i", $id_servicio);
$stmt_plazas->execute();
$result_plazas = $stmt_plazas->get_result();

if ($result_plazas->num_rows === 0) {
    echo "<div class='alert alert-warning text-center'>No hay plazas disponibles para este servicio social en este momento.</div>";
    echo "<div class='text-center mt-4'>";
    echo "<a href='ServiciosSociales.php' class='btn btn-secondary'><i class='fas fa-arrow-left'></i> Volver a Servicios</a>";
    echo "</div>";
    exit;
}
?>

<?php include 'barra.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container">
            <h1 class="text-center mb-5">Servicio Social - Detalles del Servicio</h1>

            <div class="card mb-5">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Servicio</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($servicio['Programa']); ?></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-text"><strong><i class="fas fa-building"></i> Dependencia:</strong><br><?php echo htmlspecialchars($servicio['dependencia']); ?></p>
                            <p class="card-text"><strong><i class="fas fa-user-tie"></i> Encargado:</strong><br><?php echo htmlspecialchars($servicio['Encargado_N'] . " " . $servicio['Encargado_A']); ?></p>
                            <p class="card-text"><strong><i class="fas fa-sitemap"></i> Departamento:</strong><br><?php echo htmlspecialchars($servicio['Departamento']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="card-text"><strong><i class="fas fa-user-check"></i> Responsable:</strong><br><?php echo htmlspecialchars($servicio['responsable_nombre']); ?></p>
                            <p class="card-text"><strong><i class="fas fa-briefcase"></i> Puesto:</strong><br><?php echo htmlspecialchars($servicio['responsable_puesto']); ?></p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="card-text"><strong><i class="fas fa-tasks"></i> Actividades:</strong></p>
                        <div class="bg-light p-3 rounded">
                            <?php echo nl2br(htmlspecialchars($servicio['Actividades'])); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Horarios Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        while ($plaza = $result_plazas->fetch_assoc()) : 
                        ?>
                            <div class="col-lg-6 col-md-12 mb-3">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="card-title text-success">
                                                    <i class="fas fa-graduation-cap"></i> 
                                                    <?php echo htmlspecialchars($plaza['Carrera']); ?>
                                                </h6>
                                                <p class="card-text mb-2">
                                                    <i class="fas fa-clock text-primary"></i> 
                                                    <strong>Horario:</strong> <?php echo htmlspecialchars($plaza['Horario']); ?>
                                                </p>
                                                <p class="card-text mb-0">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-users"></i> 
                                                        <?php echo $plaza['Cupo']; ?> lugar(es) disponible(s)
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <form action="seleccionar_cupo.php" method="POST" class="mt-auto">
                                            <input type="hidden" name="id_servicio" value="<?php echo $id_servicio; ?>">
                                            <input type="hidden" name="id_cupo" value="<?php echo $plaza['Id']; ?>">
                                            <input type="hidden" name="carrera" value="<?php echo htmlspecialchars($plaza['Carrera']); ?>">
                                            <input type="hidden" name="horario" value="<?php echo htmlspecialchars($plaza['Horario']); ?>">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-hand-point-up"></i> Seleccionar Este Horario
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="ServiciosSociales.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Servicios
                </a>
                <a href="../../Incluye/alumno/IndexAlum.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Ir al Dashboard
                </a>
            </div>
        </div>
    </main>
	<br>
    <?php include '../../Modulos/footer.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/scripts.js"></script>
</body>
</html>