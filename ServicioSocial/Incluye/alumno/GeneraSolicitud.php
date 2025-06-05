<?php
session_start();

require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

include '../../Modulos/conexion.php';
include '../../Seguridad/control_sesion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['Periodo']) &&
        !empty($_POST['Modalidad']) &&
        !empty($_POST['FechaInicio']) &&
        !empty($_POST['FechaTermino'])
    ) {
        $no_control = $_SESSION['no_control'];

        // Datos del alumno con dirección
        $stmt = $enlace->prepare("
            SELECT 
                a.*,
                d.Calle,
                d.Colonia,
                d.Estado,
                d.Ciudad,
                d.CP,
                d.NumeroExt,
                d.NumeroInt
            FROM alumno a
            LEFT JOIN direccion d ON a.Id_Direccion = d.Id
            WHERE a.No_Control = ?
        ");
        $stmt->bind_param("s", $no_control);
        $stmt->execute();
        $result = $stmt->get_result();
        $alumno = $result->fetch_assoc();

        if (!$alumno) {
            echo "<script>alert('No se encontró al alumno.');</script>";
            exit;
        }

        // Datos del servicio completos
        $stmt = $enlace->prepare("
            SELECT 
                s.Programa,
                s.Actividades,
                d.Nombre as Dependencia_Nombre,
                d.Encargado_N,
                d.Encargado_A,
                d.Puesto_En,
                r.nombre as Responsable_Nombre,
                r.puesto as Responsable_Puesto
            FROM inscripciones i
            INNER JOIN servicio s ON i.Id_Servicio = s.Id
            INNER JOIN dependencia d ON i.Id_Dependencia = d.Id
            LEFT JOIN responsable r ON i.Id_Rspnb = r.id
            WHERE i.Id_Alumno = ?
        ");
        $stmt->bind_param("s", $no_control);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos_servicio = $result->fetch_assoc();

        if (!$datos_servicio) {
            echo "<script>alert('No se encontraron datos del servicio para este alumno.');</script>";
            exit;
        }

        // Capturar datos del formulario
        $periodo = $_POST['Periodo'];
        $modalidad = $_POST['Modalidad'];
        $fecha_inicio = $_POST['FechaInicio'];
        $fecha_termino = $_POST['FechaTermino'];

        // Convertir fechas al formato DD/MM/YYYY
        $fecha_i = date_create($fecha_inicio);
        $fecha_f = date_create($fecha_termino);
        $finicio = date_format($fecha_i, "d/m/Y");
        $fterminacion = date_format($fecha_f, "d/m/Y");

        // Preparar datos del domicilio
        $domicilio = $alumno['Calle'] . ' ' . $alumno['NumeroExt'];
        if ($alumno['NumeroInt']) {
            $domicilio .= ' Int. ' . $alumno['NumeroInt'];
        }
        $domicilio .= ', Col. ' . $alumno['Colonia'] . ', ' . $alumno['Ciudad'] . ', ' . $alumno['Estado'] . ' C.P. ' . $alumno['CP'];

        // Preparar sexo
        $sexo = ($alumno['Genero'] == 'M') ? 'Masculino' : 'Femenino';

        // Preparar titular de dependencia
        $titular_dependencia = $datos_servicio['Encargado_N'] . ' ' . $datos_servicio['Encargado_A'];

        $timestamp = time();

        // Crear documento Word
        $temp_dir = sys_get_temp_dir();
        $doc_path = tempnam($temp_dir, 'servicio_') . '.docx';
        
        // Aquí debes especificar la ruta de tu plantilla Word
        $templateProcessor = new TemplateProcessor('../../Doc/HojaC.docx');
        
        // Datos personales del alumno
        $templateProcessor->setValue('Nombre', $alumno['Nombre']);
        $templateProcessor->setValue('ApellidoP', $alumno['Apellido_P']);
        $templateProcessor->setValue('ApellidoM', $alumno['Apellido_M']);
        $templateProcessor->setValue('Sexo', $sexo);
        $templateProcessor->setValue('Telefono', $alumno['Telefono']);
        $templateProcessor->setValue('Domicilio', $domicilio);
        $templateProcessor->setValue('Correo', $alumno['Correo']);
        
        // Datos de escolaridad
        $templateProcessor->setValue('NoControl', $alumno['No_Control']);
        $templateProcessor->setValue('Carrera', $alumno['Carrera']);
        $templateProcessor->setValue('Semestre', $alumno['Semestre']);
        
        // Datos del período y servicio
        $templateProcessor->setValue('Periodo', $periodo);
        $templateProcessor->setValue('Modalidad', $modalidad);
        $templateProcessor->setValue('Finicio', $finicio);
        $templateProcessor->setValue('Fterminacion', $fterminacion);
        
        // Datos del programa de servicio social
        $templateProcessor->setValue('Dependencia', $datos_servicio['Dependencia_Nombre']);
        $templateProcessor->setValue('TitularD', $titular_dependencia);
        $templateProcessor->setValue('PuestoD', $datos_servicio['Puesto_En']);
        $templateProcessor->setValue('Programa', $datos_servicio['Programa']);
        $templateProcessor->setValue('Actividades', $datos_servicio['Actividades']);
        
        $templateProcessor->saveAs($doc_path);

        // Descargar documento
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="Servicio_Social_' . $no_control . '_' . $timestamp . '.docx"');
        header('Content-Length: ' . filesize($doc_path));
        readfile($doc_path);

        // Eliminar archivo temporal
        unlink($doc_path);
        exit;
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración Servicio Social</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php include 'barra.php'; ?>
<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">
    <h2 class="mt-4 text-center">Solicitud de Servicio Social</h2>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST">
                        <h4 class="text-primary text-center mb-4">¡Completa tu solicitud de servicio social!</h4>
                        <p class="card-text">Completa los datos de tu Solicitud de servicio social para generar la documentación correspondiente.</p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Semestre en el que estas</label>
                                <select name="Periodo" class="form-control" required>
    <option value="">Selecciona el período</option>
    <option value="AGO-DIC <?php echo date('Y') - 1; ?>">Agosto - Diciembre <?php echo date('Y') - 1; ?></option>
    <option value="ENE-JUN <?php echo date('Y'); ?>">Enero - Junio <?php echo date('Y'); ?></option>
    <option value="AGO-DIC <?php echo date('Y'); ?>">Agosto - Diciembre <?php echo date('Y'); ?></option>
</select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modalidad de Servicio</label>
                                <select name="Modalidad" class="form-control" required>
                                    <option value="">Selecciona la modalidad</option>
                                    <option value="Interno">Interno</option>
                                    <option value="Externo">Externo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Fecha de Inicio del Servicio</label>
        <input type="date" name="FechaInicio" id="fechaInicio" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Fecha de Terminación del Servicio</label>
        <input type="date" name="FechaTermino" id="fechaTermino" class="form-control" required>
    </div>
</div>


                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Generar Documento
                            </button>
                        </div>
                    </form>
                    <hr>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Una vez completados todos los campos, se generará automáticamente tu documento de servicio social con la información proporcionada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<?php include '../../Modulos/footer.php'; ?>
</div>
<script src="../../js/scripts.js"></script>
        <script>
    const hoy = new Date();
    const año = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const dia = String(hoy.getDate()).padStart(2, '0');

    const minFecha = `${año - 1}-${mes}-${dia}`;
    const maxFecha = `${año + 3}-${mes}-${dia}`;

    document.getElementById('fechaInicio').min = minFecha;
    document.getElementById('fechaInicio').max = maxFecha;
    document.getElementById('fechaTermino').min = minFecha;
    document.getElementById('fechaTermino').max = maxFecha;
</script>
</body>
</html>
