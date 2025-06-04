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
        // Consulta para obtener los datos del alumno basado en el No_Control (ahora incluyendo créditos)
        $alumno_query = "SELECT Nombre, Apellido_P, Apellido_M, No_Control, Carrera, Correo, Telefono, Id_Direccion, Créditos FROM alumno WHERE No_Control = ?";
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
                <!-- Contenedor de Información Personal -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header bg text-white">
                                <h1 class="card-title mb-0">¡Bienvenido, <?php echo isset($alumno_row['Nombre']) && isset($alumno_row['Apellido_P']) && isset($alumno_row['Apellido_M'])
                                                                                ? $alumno_row['Nombre'] . ' ' . $alumno_row['Apellido_P'] . ' ' . $alumno_row['Apellido_M'] : 'Usuario'; ?>! </h1>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Nombre:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Nombre']) ? $alumno_row['Nombre'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Apellido Paterno:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Apellido_P']) ? $alumno_row['Apellido_P'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Apellido Materno:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Apellido_M']) ? $alumno_row['Apellido_M'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Número de Control:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['No_Control']) ? $alumno_row['No_Control'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Carrera:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Carrera']) ? $alumno_row['Carrera'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Correo Electrónico:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Correo']) ? $alumno_row['Correo'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Número de Teléfono:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Telefono']) ? $alumno_row['Telefono'] : 'No disponible'; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Créditos:</div>
                                    <div class="col-sm-4"><?php echo isset($alumno_row['Créditos']) ? $alumno_row['Créditos'] : 'No disponible'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenedor de Dirección -->
                <div class="row mb-5"> <!-- Agregamos mb-5 para dar espacio entre el contenido y el footer -->
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header bg text-white">
                                <h2 class="card-title mb-0">Dirección</h2>
                            </div>
                            <div class="card-body">
                                <?php
                                if ($direccion_result->num_rows > 0) {
                                    $direccion_row = $direccion_result->fetch_assoc();
                                ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Calle:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Calle']; ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Número Externo:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Numero_Externo']; ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Número Interno:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Numero_Interno']; ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Colonia:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Colonia']; ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Código Postal:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Codigo_Postal']; ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Municipio:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Municipio']; ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 fw-bold">Estado:</div>
                                        <div class="col-sm-4"><?php echo $direccion_row['Estado']; ?></div>
                                    </div>
                                <?php
                                } else {
                                    echo "<div class='alert alert-warning'>No se encontró la dirección del alumno.</div>";
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