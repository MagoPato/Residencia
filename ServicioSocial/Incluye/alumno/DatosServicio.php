<?php
session_start();  // Inicia la sesión
include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos
include '../../Seguridad/control_sesion.php'; 
// Al inicio del archivo PHP

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['no_control'])) { // Ajuste para usar 'no_control'
    $usuario_id = $_SESSION['no_control'];  // Obtener el ID del usuario desde la sesión

    try {
        // Consulta para obtener los datos del alumno basado en el No_Control
        $alumno_query = "SELECT Nombre, Apellido_P, Apellido_M, No_Control, Carrera, Correo, Telefono, Id_Direccion FROM alumno WHERE No_Control = ?";
        $alumno_stmt = $enlace->prepare($alumno_query);
        $alumno_stmt->bind_param("s", $usuario_id);  // Enlazar el No_Control con el parámetro de la consulta
        $alumno_stmt->execute();
        $alumno_result = $alumno_stmt->get_result();

        if ($alumno_result->num_rows > 0) {
            $alumno_row = $alumno_result->fetch_assoc();
            $id_direccion = $alumno_row['Id_Direccion'];  // Obtener ID de la dirección del alumno

            // Consulta para obtener la dirección basada en el Id_Direccion
            $direccion_query = "SELECT Calle, NumeroExt AS Numero_Externo, NumeroInt AS Numero_Interno, Colonia, CP AS Codigo_Postal, Ciudad AS Municipio, Estado 
                                FROM direccion WHERE Id = ?";
            $direccion_stmt = $enlace->prepare($direccion_query);
            $direccion_stmt->bind_param("i", $id_direccion);  // Enlazar el ID de dirección
            $direccion_stmt->execute();
            $direccion_result = $direccion_stmt->get_result();

            // Consulta para obtener los datos del servicio social del alumno
            $servicio_query = "SELECT 
                                i.ID as Inscripcion_ID,
                                s.Programa,
                                s.Departamento,
                                s.Actividades,
                                s.Tipo,
                                d.Nombre as Dependencia_Nombre,
                                d.Encargado_N,
                                d.Encargado_A,
                                d.Puesto_En,
                                r.nombre as Responsable_Nombre,
                                r.puesto as Responsable_Puesto,
                                c.Cupo,
                                c.Carrera as Carrera_Cupo,
                                c.Horario
                               FROM inscripciones i
                               INNER JOIN servicio s ON i.Id_Servicio = s.Id
                               INNER JOIN dependencia d ON i.Id_Dependencia = d.Id
                               INNER JOIN responsable r ON i.Id_Rspnb = r.id
                               INNER JOIN cupos c ON i.Id_Cupo = c.Id
                               WHERE i.Id_Alumno = ?";
            $servicio_stmt = $enlace->prepare($servicio_query);
            $servicio_stmt->bind_param("s", $usuario_id);
            $servicio_stmt->execute();
            $servicio_result = $servicio_stmt->get_result();

        } else {
            echo "<div class='alert alert-warning'>No se encontró el alumno con el No_Control: " . htmlspecialchars($usuario_id) . "</div>";
            exit;  // Salir si no se encuentra al alumno
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
} else {
     // Opcional: Registrar intento de acceso no autorizado
    error_log("Intento de acceso no autorizado el " . date('Y-m-d H:i:s') . " desde IP: " . $_SERVER['REMOTE_ADDR']);

    // Redirigir al usuario al login si no está autenticado
   	header("Location: " . "https://" . $_SERVER['HTTP_HOST']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Servicio social, Tecnl" />
    <meta name="author" content="Tecnl" />
    <title>Servicio Social - Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />

    <!-- Iconos -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
         <style>
        .bg {
            background-color: #365CB2 !important; /* Cambia el fondo a verde */
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">


                <!-- Contenedor de Información del Servicio Social -->
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header bg text-white">
                                <h2 class="card-title mb-0"><i class="fas fa-hands-helping me-2"></i>Servicio Social</h2>
                            </div>
                            <div class="card-body">
                                <?php
                                if ($servicio_result->num_rows > 0) {
                                    $servicio_row = $servicio_result->fetch_assoc();
                                ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Programa:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Programa']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Departamento:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Departamento']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Tipo de Servicio:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Tipo']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Horario:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Horario']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Actividades:</div>
                                        <div class="col-sm-8">
                                            <?php 
                                            $actividades = nl2br(htmlspecialchars($servicio_row['Actividades']));
                                            echo $actividades; 
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3"><i class="fas fa-building me-2"></i>Dependencia</h5>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Nombre:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Dependencia_Nombre']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Encargado:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Encargado_N'] . ' ' . $servicio_row['Encargado_A']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Puesto del Encargado:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Puesto_En']); ?></div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3"><i class="fas fa-user-tie me-2"></i>Responsable del Servicio</h5>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Nombre:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Responsable_Nombre']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Puesto:</div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($servicio_row['Responsable_Puesto']); ?></div>
                                    </div>
                                <?php
                                } else {
                                    echo "<div class='alert alert-info'><i class='fas fa-info-circle me-2'></i>No estás inscrito en ningún servicio social actualmente.</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </main>
         <?php include '../../Modulos/footer.php'; ?>
    </div>
</body>
<script src="../../js/scripts.js"></script>
</html>