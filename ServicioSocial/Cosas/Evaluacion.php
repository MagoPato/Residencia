<?php
require '../dev/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

$conexion = new mysqli('localhost', 'root', '', 'servicio_social');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$idAlumno = $_GET['id'];
$sql = "SELECT alumno.Nombre, alumno.Apellido_P, alumno.Apellido_M, servicio.Programa
        FROM alumno
        LEFT JOIN servicio ON alumno.Id_Servicio = servicio.Id
        WHERE alumno.No_Control = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $idAlumno);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    $templateWord = new TemplateProcessor('../dev/Doc/Evalucion.docx');

    $templateWord->setValue('Prestador', $fila['Nombre'] . ' ' . $fila['Apellido_P'] . ' ' . $fila['Apellido_M']);
    $templateWord->setValue('Responsable', $fila['Programa']);
    $templateWord->setValue('FechaI', '2024-01-01');
    $templateWord->setValue('FechaT', '2024-06-30');

    $outputDocPath = 'Evaluacion_Generada.docx';
    $templateWord->saveAs($outputDocPath);

    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header("Content-Disposition: attachment; filename=Evaluacion_Generada.docx");
    readfile($outputDocPath);
    unlink($outputDocPath);
} else {
    echo "No se encontraron datos para el alumno.";
}

$conexion->close();
?>