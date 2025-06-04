<?php
session_start();  // Inicia la sesión
include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos
include '../../Seguridad/control_sesion.php'; 

$alumno = null;

// Buscar alumno por número de control
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['no_control'])) {
    $no_control = mysqli_real_escape_string($enlace, $_GET['no_control']);

    $query_alumno = "SELECT a.*, d.Calle, d.Colonia, d.Estado, d.Ciudad AS Municipio, d.CP, d.NumeroExt, d.NumeroInt
                     FROM alumno a
                     LEFT JOIN direccion d ON a.Id_Direccion = d.Id
                     WHERE a.No_Control = '$no_control'";
    $result_alumno = mysqli_query($enlace, $query_alumno);

    if ($result_alumno && mysqli_num_rows($result_alumno) > 0) {
        $alumno = mysqli_fetch_assoc($result_alumno);
    } else {
        echo "<script>alert('Alumno no encontrado');</script>";
    }
}

// Obtener todos los programas de servicios disponibles
$servicios = [];
$query_servicio = "SELECT DISTINCT Programa FROM servicio";
$result_servicio = mysqli_query($enlace, $query_servicio);
while ($row = mysqli_fetch_assoc($result_servicio)) {
    $servicios[] = $row['Programa'];
}

// Obtener horarios únicos y agruparlos en bloques de 4
$horarios = [];
$query_horario = "SELECT DISTINCT Horario FROM cupos";
$result_horario = mysqli_query($enlace, $query_horario);
while ($row = mysqli_fetch_assoc($result_horario)) {
    $horarios[] = $row['Horario'];
}

// Procesar actualización del alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_control = mysqli_real_escape_string($conexion, $_POST['no_control']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido_p = mysqli_real_escape_string($conexion, $_POST['apellido_paterno']);
    $apellido_m = mysqli_real_escape_string($conexion, $_POST['apellido_materno']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $carrera = mysqli_real_escape_string($conexion, $_POST['carrera']);
    $créditos = mysqli_real_escape_string($conexion, $_POST['créditos']);
    $direccion_calle = mysqli_real_escape_string($conexion, $_POST['direccion_calle']);
    $direccion_colonia = mysqli_real_escape_string($conexion, $_POST['direccion_colonia']);
    $direccion_estado = mysqli_real_escape_string($conexion, $_POST['direccion_estado']);
    $direccion_municipio = mysqli_real_escape_string($conexion, $_POST['direccion_municipio']);
    $direccion_codigo_postal = mysqli_real_escape_string($conexion, $_POST['direccion_codigo_postal']);
    $direccion_numero_exterior = mysqli_real_escape_string($conexion, $_POST['direccion_numero_exterior']);
    $direccion_numero_interior = mysqli_real_escape_string($conexion, $_POST['direccion_numero_interior']);
    $servicio = mysqli_real_escape_string($conexion, $_POST['servicio']);
    $turno = mysqli_real_escape_string($conexion, $_POST['turno']);
    $horario = mysqli_real_escape_string($conexion, $_POST['horario']);

    // Actualizar dirección
    $query_update_direccion = "UPDATE direccion SET Calle='$direccion_calle', Colonia='$direccion_colonia',
                                Estado='$direccion_estado', Ciudad='$direccion_municipio', CP='$direccion_codigo_postal',
                                NumeroExt='$direccion_numero_exterior', NumeroInt='$direccion_numero_interior'
                                WHERE Id=(SELECT Id_Direccion FROM alumno WHERE No_Control='$no_control')";
    mysqli_query($conexion, $query_update_direccion);

    // Actualizar alumno incluyendo la columna Créditos
    $query_update_alumno = "UPDATE alumno SET Nombre='$nombre', Apellido_P='$apellido_p', Apellido_M='$apellido_m',
                            Telefono='$telefono', Carrera='$carrera', Creditos='$créditos', Turno='$turno'
                            WHERE No_Control='$no_control'";
    mysqli_query($conexion, $query_update_alumno);

    echo "<script>alert('Datos actualizados correctamente'); window.location.href='modificar_alumno.php?no_control=$no_control';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Modificar Alumno - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css"  rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"  crossorigin="anonymous"></script>
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Modificar Alumno</h1>
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST">
                            <!-- Campo oculto para el número de control -->
                            <input type="hidden" name="no_control" value="<?= $alumno['No_Control'] ?? '' ?>">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="numero_control" class="form-label">Número de Control</label>
                                    <input type="text" class="form-control" id="numero_control" name="numero_control" required>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="button" class="btn btn-primary" onclick="buscarAlumno()">Buscar</button>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required value="<?= $alumno['Nombre'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required value="<?= $alumno['Apellido_P'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required value="<?= $alumno['Apellido_M'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" required value="<?= $alumno['Telefono'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="carrera" class="form-label">Carrera</label>
                                    <select class="form-select" id="carrera" name="carrera" required>
                                        <option value="">Selecciona una carrera</option>
                                        <?php
                                        $carreras = [
                                            "Ingeniería en Sistemas Computacionales",
                                            "Ingeniería en Gestión Empresarial",
                                            "Ingeniería en Semiconductores",
                                            "Ingeniería Electromecánica",
                                            "Ingeniería Mecatrónica",
                                            "Ingeniería Electrónica",
                                            "Ingeniería Ambiental",
                                            "Ingeniería Industrial"
                                        ];
                                        foreach ($carreras as $carrera_opcion) {
                                            $selected = ($alumno['Carrera'] ?? '') == $carrera_opcion ? 'selected' : '';
                                            echo "<option value=\"$carrera_opcion\" $selected>$carrera_opcion</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="creditos" class="form-label">Créditos</label>
                                    <input type="number" class="form-control" id="creditos" name="creditos" min="0" step="1" required value="<?= $alumno['Créditos'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="direccion_calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="direccion_calle" name="direccion_calle" required value="<?= $alumno['Calle'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_numero_exterior" class="form-label">Número Exterior</label>
                                    <input type="text" class="form-control" id="direccion_numero_exterior" name="direccion_numero_exterior" required value="<?= $alumno['NumeroExt'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_numero_interior" class="form-label">Número Interior</label>
                                    <input type="text" class="form-control" id="direccion_numero_interior" name="direccion_numero_interior" value="<?= $alumno['NumeroInt'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="direccion_colonia" class="form-label">Colonia</label>
                                    <input type="text" class="form-control" id="direccion_colonia" name="direccion_colonia" required value="<?= $alumno['Colonia'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_codigo_postal" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="direccion_codigo_postal" name="direccion_codigo_postal" required value="<?= $alumno['CP'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_municipio" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="direccion_municipio" name="direccion_municipio" required value="<?= $alumno['Municipio'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="direccion_estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="direccion_estado" name="direccion_estado" required value="<?= $alumno['Estado'] ?? '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="servicio" class="form-label">Programa de Servicio</label>
                                    <select class="form-select" id="servicio" name="servicio" required>
                                        <option value="">Selecciona un programa</option>
                                        <?php foreach ($servicios as $servicio_opcion): ?>
                                            <option value="<?= $servicio_opcion ?>" <?= ($alumno['Servicio'] ?? '') == $servicio_opcion ? 'selected' : '' ?>>
                                                <?= $servicio_opcion ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="turno" class="form-label">Turno</label>
                                    <select class="form-select" id="turno" name="turno" required>
                                        <option value="">Selecciona un turno</option>
                                        <option value="M" <?= ($alumno['Turno'] ?? '') == 'M' ? 'selected' : '' ?>>Mañana</option>
                                        <option value="T" <?= ($alumno['Turno'] ?? '') == 'T' ? 'selected' : '' ?>>Tarde</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="horario" class="form-label">Horario</label>
                                    <select class="form-select" id="horario" name="horario" required>
                                        <option value="">Selecciona un horario</option>
                                        <?php
                                        $count = 0;
                                        foreach ($horarios as $hora) {
                                            if ($count % 4 == 0 && $count != 0) echo '</optgroup><optgroup label="Grupo ' . ((int)($count / 4) + 1) . '">';
                                            echo "<option value=\"$hora\">$hora</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2 align-self-end">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                                <div class="col-md-2 align-self-end">
                                    <button type="button" class="btn btn-danger" onclick="eliminarAlumno()">Eliminar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../../Modulos/footer.php'; ?>
    </div>
    <script>
        function buscarAlumno() {
            const numeroControl = document.getElementById("numero_control").value;
            if (numeroControl) {
                window.location.href = "?no_control=" + encodeURIComponent(numeroControl);
            } else {
                alert("Por favor ingresa un número de control");
            }
        }

        function eliminarAlumno() {
            const numeroControl = "<?= $alumno['No_Control'] ?? '' ?>";
            if (confirm("¿Estás seguro de eliminar a este alumno?")) {
                window.location.href = "eliminar_alumno.php?no_control=" + encodeURIComponent(numeroControl);
            }
        }
    </script>
</body>
</html>