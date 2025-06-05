<?php
session_start();

require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

include '../../Modulos/conexion.php';
include '../../Seguridad/control_sesion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['NumReport']) &&
        !empty($_POST['HorasHchs']) &&
        !empty($_POST['HorasAcm']) &&
        !empty($_POST['FechaInicio']) &&
        !empty($_POST['FechaTermino']) &&
        !empty($_POST['ResumenActividades'])
    ) {
        $no_control = $_SESSION['no_control'];

        // Datos del alumno
        $stmt = $enlace->prepare("SELECT * FROM alumno WHERE No_Control = ?");
        $stmt->bind_param("s", $no_control);
        $stmt->execute();
        $result = $stmt->get_result();
        $alumno = $result->fetch_assoc();

        if (!$alumno) {
            echo "<script>alert('No se encontró al alumno.');</script>";
            exit;
        }

        // Datos del servicio
        $stmt = $enlace->prepare("
            SELECT 
                s.Programa,
                d.Nombre as Dependencia_Nombre,
                r.Nombre as Responsable_Nombre
            FROM inscripciones i
            INNER JOIN servicio s ON i.Id_Servicio = s.Id
            INNER JOIN dependencia d ON i.Id_Dependencia = d.Id
            LEFT JOIN responsable r ON i.Id_Rspnb = r.Id
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

        // Capturar entradas del formulario
        $programa = $datos_servicio['Programa'];
        $dependencia_nombre = $datos_servicio['Dependencia_Nombre'];
        $responsable_nombre = $datos_servicio['Responsable_Nombre'] ?? '';

        $num_reporte = $_POST['NumReport'];
        $horas_hechas = $_POST['HorasHchs'];
        $horas_acumuladas = $_POST['HorasAcm'];
        $fecha_inicio = $_POST['FechaInicio'];
        $fecha_termino = $_POST['FechaTermino'];
        $actividades = $_POST['ResumenActividades'];

        // Fechas en español
        $fecha_i = date_create($fecha_inicio);
        $fecha_f = date_create($fecha_termino);

        $dia_i = date_format($fecha_i, "d");
        $mes_i_en = date_format($fecha_i, "F");
        $año_i = date_format($fecha_i, "Y");

        $dia_f = date_format($fecha_f, "d");
        $mes_f_en = date_format($fecha_f, "F");
        $año_f = date_format($fecha_f, "Y");

        $meses = [
            'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
            'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
            'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
            'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
        ];
        $mes_i = $meses[$mes_i_en] ?? $mes_i_en;
        $mes_f = $meses[$mes_f_en] ?? $mes_f_en;

        $nombre_completo = $alumno['Nombre'] . ' ' . $alumno['Apellido_P'] . ' ' . $alumno['Apellido_M'];
        $timestamp = time();

        // Carpeta temporal
        $temp_dir = sys_get_temp_dir();
        $files_to_zip = [];

        // Autoevaluación
        $doc1 = tempnam($temp_dir, 'auto_') . '.docx';
        $template1 = new TemplateProcessor('../../Doc/Autoevaluacion.docx');
        $template1->setValue('Responsable', $nombre_completo);
        $template1->setValue('Programa', $programa);
        $template1->setValue('FechaR', $fecha_inicio . ' a ' . $fecha_termino);
        $template1->saveAs($doc1);
        $files_to_zip[] = $doc1;

        // Evaluación
        $doc2 = tempnam($temp_dir, 'eval_') . '.docx';
        $template2 = new TemplateProcessor('../../Doc/Evalucion.docx');
        $template2->setValue('Prestador', $nombre_completo);
        $template2->setValue('Responsable', $programa);
        $template2->setValue('FechaI', $fecha_inicio);
        $template2->setValue('FechaT', $fecha_termino);
        $template2->saveAs($doc2);
        $files_to_zip[] = $doc2;

        // Reporte Bimestral
        $doc3 = tempnam($temp_dir, 'rep_') . '.docx';
        $template3 = new TemplateProcessor('../../Doc/Reporte Bimestral.docx');
        $template3->setValue('NoReporte', $num_reporte);
        $template3->setValue('Nombre', $alumno['Nombre']);
        $template3->setValue('ApellidoP', $alumno['Apellido_P']);
        $template3->setValue('ApellidoM', $alumno['Apellido_M']);
        $template3->setValue('Carrera', $alumno['Carrera']);
        $template3->setValue('NoControl', $no_control);
        $template3->setValue('DiaI', $dia_i);
        $template3->setValue('MesI', $mes_i);
        $template3->setValue('AñoI', $año_i);
        $template3->setValue('DiaF', $dia_f);
        $template3->setValue('MesF', $mes_f);
        $template3->setValue('AñoF', $año_f);
        $template3->setValue('Dependencia', $dependencia_nombre);
        $template3->setValue('Programa', $programa);
        $template3->setValue('Actividades', $actividades);
        $template3->setValue('HorasR', $horas_hechas);
        $template3->setValue('HorasA', $horas_acumuladas);
        $template3->saveAs($doc3);
        $files_to_zip[] = $doc3;

        // Crear archivo ZIP temporal
        $zip_path = tempnam($temp_dir, 'zip_') . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
            foreach ($files_to_zip as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();

            // Descargar ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="Reportes_' . $no_control . '_' . $timestamp . '.zip"');
            header('Content-Length: ' . filesize($zip_path));
            readfile($zip_path);

            // Eliminar archivos temporales
            foreach ($files_to_zip as $file) {
                unlink($file);
            }
            unlink($zip_path);
            exit;
        } else {
            echo "<script>alert('No se pudo crear el archivo ZIP.');</script>";
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}
?>

<!-- HTML (Form) -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Formulario Servicio Social</title>
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
    <h2 class="mt-4 text-center">Carga de Horas de Servicio Social</h2>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST">
                        <h4 class="text-primary text-center mb-4">¡Tu Reporte de Horas y Actividades!</h4>
                            <p class="card-text">En este lugar Registraras tus horas realizadas en el bimestre, asi como las acumuladas en tu estancia, recuerda poner solo las horas realizads y registrar las acumuladas que se te mandaron en el instructivo</p>
                        <div class="row mb-3 text-center">
                            <div class="col-md-4">
                                <label>Numero de Reporte</label>
                                <input type="number" name="NumReport" class="form-control" required min="0" required>
                            </div>
                            <div class="col-md-4">
                            <label>Horas hechas</label>
                            <input type="number" name="HorasHchs" class="form-control" required min="0" max="480">
                        </div>
                        <div class="col-md-4">
                            <label>Horas acumuladas</label>
                            <input type="number" name="HorasAcm" class="form-control" required min="0" max="480">
                        </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Fecha de Inicio</label>
                                <input type="date" name="FechaInicio" id="fechaInicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Fecha de Término</label>
                                <input type="date" name="FechaTermino" id="fechaTermino" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Resumen de Actividades</label>
                            <textarea name="ResumenActividades" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Enviar Datos</button>
                        </div>
                    </form>
                    <hr>
                    <p>Recuerda que los Reportes son cruciales y deben ser entregados antes de la fecha límite. No te preocupes, estaremos actualizando las fechas de entrega en nuestra página de Facebook de TecNL. ¡Mantente atento a nuestras publicaciones para no perderte ninguna información importante!</p>
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
