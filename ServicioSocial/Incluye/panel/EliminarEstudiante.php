<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../Modulos/conexion.php';

// Obtener el ID del alumno desde la URL (No_Control)
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Procesar la solicitud de eliminación si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    try {
        // Iniciar transacción
        mysqli_begin_transaction($enlace);

        // Obtener Id_Direccion del alumno
        $sql1 = "SELECT Id_Direccion FROM alumno WHERE No_Control = ?";
        $stmt1 = $enlace->prepare($sql1);
        $stmt1->bind_param("s", $id);
        $stmt1->execute();
        $result = $stmt1->get_result();
        $alumno = $result->fetch_assoc();
        $id_direccion = $alumno['Id_Direccion'] ?? null;

        // Eliminar alumno
        $sql2 = "DELETE FROM alumno WHERE No_Control = ?";
        $stmt2 = $enlace->prepare($sql2);
        $stmt2->bind_param("s", $id);
        $stmt2->execute();

        // Eliminar dirección si existe
        if ($id_direccion) {
            $sql3 = "DELETE FROM direccion WHERE Id = ?";
            $stmt3 = $enlace->prepare($sql3);
            $stmt3->bind_param("i", $id_direccion);
            $stmt3->execute();
        }

        // Confirmar transacción
        mysqli_commit($enlace);

        // Redirigir a acept_alumno.php
        header("Location: acept_alumno.php");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($enlace);
        // Puedes loguear el error si gustas, pero no mostrarlo en producción
        header("Location: acept_alumno.php");
        exit();
    } finally {
        if (isset($stmt1)) $stmt1->close();
        if (isset($stmt2)) $stmt2->close();
        if (isset($stmt3)) $stmt3->close();
        $enlace->close();
    }
}

// Obtener datos del alumno para mostrar
if ($id) {
    $sql = "
        SELECT 
            a.Nombre, a.Apellido_P, a.Apellido_M, a.No_Control, a.Carrera, a.Correo, a.Telefono, a.Semestre,
            c.Estatus, c.Tipo_Usuario
        FROM 
            alumno a
        LEFT JOIN 
            cuenta c ON a.No_Control = c.Usuario
        WHERE 
            a.No_Control = ?";
    $stmt = $enlace->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $alumno = ($result->num_rows > 0) ? $result->fetch_assoc() : null;
    $stmt->close();
} else {
    $alumno = null;
}

$enlace->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Servicio Social - Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />    
</head>
<body class="sb-nav-fixed">
<?php include 'barra.php'; ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-5">
            <h5 class="text-center">Detalles del Alumno Rechazado</h5>
            <?php if ($alumno): ?>
                <div class="container mt-12">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $alumno['Nombre'] . ' ' . $alumno['Apellido_P'] . ' ' . $alumno['Apellido_M']; ?></h5>
                                    <p><strong>No. Control:</strong> <?php echo $alumno['No_Control']; ?></p>
                                    <p><strong>Carrera:</strong> <?php echo $alumno['Carrera']; ?></p>
                                    <p><strong>Correo:</strong> <?php echo $alumno['Correo']; ?></p>
                                    <p><strong>Semestre:</strong> <?php echo $alumno['Semestre']; ?></p>
                                    <p><strong>Teléfono:</strong> <?php echo $alumno['Telefono']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para eliminar al estudiante -->
                    <div class="mt-4 text-center">
                        <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este estudiante?');">
                            <input type="hidden" name="eliminar" value="1">
                            <button type="submit" class="btn btn-danger">Eliminar Estudiante</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    No se encontraron detalles para el alumno solicitado.
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </main>
    <?php include '../../Modulos/footer.php'; ?>
</div>
</body>
</html>
