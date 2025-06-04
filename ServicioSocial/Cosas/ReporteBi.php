<?php
require '../dev/vendor/autoload.php'; // Asegúrate de cargar PHPWord correctamente

use PhpOffice\PhpWord\TemplateProcessor;

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'servicio_social');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de datos
$idAlumno = $_GET['id']; // ID del alumno recibido como parámetro
$sql = "SELECT alumno.Nombre, alumno.Apellido_P, alumno.Apellido_M, alumno.No_Control, 
               alumno.Carrera, servicio.Programa, dependencia.Nombre AS Dependencia,
               reporte.No_Reporte, reporte.Horas_Rep, reporte.Horas_Acu, direccion.Calle,
               direccion.Colonia, direccion.Estado, direccion.Ciudad, direccion.CP
        FROM alumno
        LEFT JOIN direccion ON alumno.Id_Direccion = direccion.Id
        LEFT JOIN reporte ON reporte.Id_Alumno = alumno.No_Control
        LEFT JOIN servicio ON alumno.Id_Servicio = servicio.Id
        LEFT JOIN dependencia ON servicio.Id_Dependencia = dependencia.Id
        WHERE alumno.No_Control = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $idAlumno);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    $templateWord = new TemplateProcessor('C:/Users/josel/Downloads/Reporte Bimestral.docx');

    // Rellenar plantilla
    $templateWord->setValue('Nombre', $fila['Nombre']);
    $templateWord->setValue('ApellidoP', $fila['Apellido_P']);
    $templateWord->setValue('ApellidoM', $fila['Apellido_M']);
    $templateWord->setValue('NoControl', $fila['No_Control']);
    $templateWord->setValue('Carrera', $fila['Carrera']);
    $templateWord->setValue('Programa', $fila['Programa']);
    $templateWord->setValue('Dependencia', $fila['Dependencia']);
    $templateWord->setValue('NoReporte', $fila['No_Reporte']);
    $templateWord->setValue('HorasR', $fila['Horas_Rep']);
    $templateWord->setValue('HorasA', $fila['Horas_Acu']);
    $templateWord->setValue('Domicilio', $fila['Calle'] . ' ' . $fila['Colonia'] . ' ' . $fila['Estado'] . ' ' . $fila['Ciudad'] . ' ' . $fila['CP']);

    $outputDocPath = 'Reporte_Bimestral_Generado.docx';
    $templateWord->saveAs($outputDocPath);

    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header("Content-Disposition: attachment; filename=Reporte_Bimestral_Generado.docx");
    readfile($outputDocPath);
    unlink($outputDocPath);
} else {
    echo "No se encontraron datos para el alumno.";
}

$conexion->close();
?>
