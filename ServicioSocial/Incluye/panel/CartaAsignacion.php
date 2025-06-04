<?php
session_start();

require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

include '../../Modulos/conexion.php';
include '../../Seguridad/control_sesion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['NumeroControl']) &&
        !empty($_POST['FechaInicio']) &&
        !empty($_POST['NoOficio']) &&
        !empty($_POST['Dirigido']) &&
        !empty($_POST['Telefono']) // Nuevo campo requerido para el teléfono
    ) {
        $numero_control = $_POST['NumeroControl'];

        // Datos del alumno
        $stmt = $enlace->prepare("
            SELECT 
                a.*
            FROM alumno a
            WHERE a.No_Control = ?
        ");
        $stmt->bind_param("s", $numero_control);
        $stmt->execute();
        $result = $stmt->get_result();
        $alumno = $result->fetch_assoc();

        if (!$alumno) {
            echo "<script>alert('No se encontró al alumno con el número de control proporcionado.');</script>";
        } else {
            // Datos del servicio completos
            $stmt = $enlace->prepare("
                SELECT 
                    s.Programa,
                    s.Departamento,
                    d.Nombre as Dependencia_Nombre,
                    c.Horario
                FROM inscripciones i
                INNER JOIN servicio s ON i.Id_Servicio = s.Id
                INNER JOIN dependencia d ON i.Id_Dependencia = d.Id
                INNER JOIN cupos c ON i.Id_Cupo = c.Id
                WHERE i.Id_Alumno = ?
            ");
            $stmt->bind_param("s", $numero_control);
            $stmt->execute();
            $result = $stmt->get_result();
            $datos_servicio = $result->fetch_assoc();

            if (!$datos_servicio) {
                echo "<script>alert('No se encontraron datos del servicio para este alumno.');</script>";
            } else {
                // Capturar datos del formulario
                $fecha_inicio = $_POST['FechaInicio'];
                $no_oficio = $_POST['NoOficio'];
                $dirigido = $_POST['Dirigido'];
                $telefono = $_POST['Telefono']; // Nuevo campo para el teléfono
                $horas_acreditadas = !empty($_POST['HorasAcreditadas']) ? $_POST['HorasAcreditadas'] : '0';
                $motivos = !empty($_POST['Motivos']) ? $_POST['Motivos'] : '';

                // Convertir fecha al formato DD/MM/YYYY
                $fecha_i = date_create($fecha_inicio);
                $finicio = date_format($fecha_i, "d/m/Y");

                // Preparar nombre completo
                $nombre_completo = $alumno['Nombre'] . ' ' . $alumno['Apellido_P'] . ' ' . $alumno['Apellido_M'];

                $timestamp = time();

                // Crear documento Word
                $temp_dir = sys_get_temp_dir();
                $doc_path = tempnam($temp_dir, 'carta_asignacion_') . '.docx';
                
                // Ruta de la plantilla de carta de asignación
                $templateProcessor = new TemplateProcessor('../../Doc/carta-asignacion.docx');
                
                // Reemplazar variables en la plantilla
                $templateProcessor->setValue('Departamento', $datos_servicio['Departamento']);
                $templateProcessor->setValue('noOficio', $no_oficio);
                $templateProcessor->setValue('DIRIGIDO', $dirigido);
                $templateProcessor->setValue('nomber', $nombre_completo);
                $templateProcessor->setValue('numerocontrol', $alumno['No_Control']);
                $templateProcessor->setValue('carrera', $alumno['Carrera']);
                $templateProcessor->setValue('programa', $datos_servicio['Programa']);
                $templateProcessor->setValue('departamento', $datos_servicio['Departamento']);
                $templateProcessor->setValue('DEPENDENCIA', $datos_servicio['Dependencia_Nombre']);
                $templateProcessor->setValue('fechas', $finicio);
                $templateProcessor->setValue('horario', $datos_servicio['Horario']);
                $templateProcessor->setValue('Acreditadas', $horas_acreditadas);
                $templateProcessor->setValue('Motivos', $motivos);
                $templateProcessor->setValue('TELEFONO', $telefono); // Nuevo campo para el teléfono
                
                $templateProcessor->saveAs($doc_path);

                // Descargar documento
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Disposition: attachment; filename="Carta_Asignacion_' . $numero_control . '_' . $timestamp . '.docx"');
                header('Content-Length: ' . filesize($doc_path));
                readfile($doc_path);

                // Eliminar archivo temporal
                unlink($doc_path);
                exit;
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos requeridos, incluyendo el teléfono.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de Carta de Asignación</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php include 'barra.php'; ?>
<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">
    <h2 class="mt-4 text-center">Carta de Asignación de Servicio Social</h2>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST">
                        <h4 class="text-primary text-center mb-4">¡Genera la carta de asignación!</h4>
                        <p class="card-text">Ingresa los datos necesarios para generar la carta de asignación de servicio social.</p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Número de Control del Alumno</label>
                                <input type="text" name="NumeroControl" class="form-control" placeholder="Ej: 20190266" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Inicio del Servicio</label>
                                <input type="date" name="FechaInicio" id="fechaInicio" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No. de Oficio</label>
                                <input type="text" name="NoOficio" class="form-control" placeholder="Ej: 1, 2, 3 o cualquier número" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirigido a</label>
                                <input type="text" name="Dirigido" class="form-control" placeholder="Nombre del responsable de la dependencia" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Teléfono de contacto</label>
                                <input type="tel" name="Telefono" class="form-control" placeholder="Ej: 5551234567" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Horas Acreditadas (opcional)</label>
                                <input type="number" name="HorasAcreditadas" class="form-control" placeholder="Si no llena nada se pondrá 0" min="0" max="480">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Motivos (opcional)</label>
                                <textarea name="Motivos" class="form-control" rows="3" placeholder="Si no llena nada se dejará en blanco"></textarea>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Generar Carta de Asignación
                            </button>
                        </div>
                    </form>
                    <hr>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Se generará automáticamente la carta de asignación con los datos del alumno y su servicio social registrado.
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
</script>
</body>
</html>