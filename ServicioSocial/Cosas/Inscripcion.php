<?php
require '../dev/vendor/autoload.php'; // Cargar PHPWord

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'servicio_social');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de datos
$sql = "SELECT 
    alumno.Nombre AS Nombre,
    alumno.Apellido_P AS ApellidoP,
    alumno.Apellido_M AS ApellidoM,
    alumno.Genero AS Sexo,
    alumno.Telefono AS Telefono,
    CONCAT_WS(' ', direccion.Calle, direccion.Colonia, direccion.Estado, direccion.Ciudad, direccion.CP) AS Domicilio,
    alumno.Correo AS Correo,
    alumno.No_Control AS NoControl,
    alumno.Carrera AS Carrera,
    'Julio-Diciembre' AS Periodo, -- Periodo fijo solicitado
    alumno.Semestre AS Semestre,
    dependencia.Nombre AS Dependencia,
    CONCAT_WS(' ', dependencia.Encargado_N, dependencia.Encargado_A) AS TitularD,
    dependencia.Puesto_En AS PuestoD,
    servicio.Programa AS Programa,
    alumno.Modalidad AS Modalidad,
    'N/A' AS Finicio, -- Ajustar si este dato se encuentra en alguna tabla
    'N/A' AS Fterminacion, -- Ajustar si este dato se encuentra en alguna tabla
    servicio.Actividades AS Actividades,
    direccion.Ciudad AS Ciudad,
    reporte.No_Reporte AS NoReporte,
    reporte.Horas_Rep AS HorasReportadas,
    reporte.Horas_Acu AS HorasAcumuladas
FROM 
    alumno
LEFT JOIN direccion ON alumno.Id_Direccion = direccion.Id
LEFT JOIN reporte ON reporte.Id_Alumno = alumno.No_Control
LEFT JOIN servicio ON alumno.Id_Servicio = servicio.Id
LEFT JOIN dependencia ON servicio.Id_Dependencia = dependencia.Id
WHERE alumno.No_Control = ''";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    // Cargar la plantilla de Word
    $templateWord = new TemplateProcessor('C:/Users/josel/Downloads/HojaC.docx');

    // Reemplazar los campos en la plantilla con los datos de la base
    $templateWord->setValue('Nombre', $fila['Nombre']);
    $templateWord->setValue('ApellidoP', $fila['Apellido_P']);
    $templateWord->setValue('ApellidoM', $fila['Apellido_M']);
    $templateWord->setValue('Sexo', $fila['Genero']);
    $templateWord->setValue('Telefono', $fila['Telefono']);
    $Domicilio = ($fila['Calle'] ?? "") . " " . ($fila['Colonia'] ?? "") . " " . ($fila['Estado'] ?? "") . " " . ($fila['Ciudad'] ?? "") . " " . ($fila['CP'] ?? "");
    $templateWord->setValue('Domicilio', $Domicilio);
    $templateWord->setValue('Correo', $fila['Correo']);
    $templateWord->setValue('NoControl', $fila['No_Control']);
    $templateWord->setValue('Carrera', $fila['Modalidad']);
    $templateWord->setValue('Periodo', "Julio-Diciembre");
    $templateWord->setValue('Semestre', $fila['Semestre']);
    $templateWord->setValue('Dependencia', $fila['Dependencia']);
    $templateWord->setValue('TitularD', $fila['TitularD']);
    $templateWord->setValue('PuestoD', $fila['PuestoD']);
    $templateWord->setValue('Programa', $fila['Programa']);
    $templateWord->setValue('Modalidad', $fila['Modalidad']);
    $templateWord->setValue('Finicio', $fila['Finicio']);
    $templateWord->setValue('Fterminacion', $fila['Fterminacion']);
    $templateWord->setValue('Actividades', $fila['Actividades']);
    $templateWord->setValue('Ciudad', $fila['Ciudad']);
    $templateWord->setValue('Dia', date("d"));
    $templateWord->setValue('Mes', date("m"));
    $templateWord->setValue('Year', date("Y"));

    // Guardar el archivo .docx
    $outputDocPath = 'Hoja_Llena.docx';
    $templateWord->saveAs($outputDocPath);

    // Configurar encabezados para descargar el archivo Word
    header("Content-Description: File Transfer");
    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header("Content-Disposition: attachment; filename=" . basename($outputDocPath));
    header("Content-Transfer-Encoding: binary");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    header("Content-Length: " . filesize($outputDocPath));
    readfile($outputDocPath);

    // Eliminar el archivo temporal si ya no es necesario
    unlink($outputDocPath);
} else {
    echo "No se encontraron datos para el ID especificado.";
}

$conexion->close();
?>