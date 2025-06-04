<?php
require '../dev/vendor/autoload.php'; // Cargar PHPWord y mPDF

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Mpdf\Mpdf;

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'servicio_social');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de datos
$sql = "SELECT * FROM alumno a, direccion d WHERE a.No_Control = 'A00001' AND a.Id_Direccion = d.Id";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    // Cargar la plantilla de Word
    $templateWord = new TemplateProcessor('../dev/Doc/HojaC.docx');

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
    $templateWord->setValue('Dependencia', $fila['Dependencia'] ?? "N/A");
    $templateWord->setValue('TitularD', $fila['TitularD'] ?? "N/A");
    $templateWord->setValue('PuestoD', $fila['PuestoD'] ?? "N/A");
    $templateWord->setValue('Programa', $fila['Programa'] ?? "N/A");
    $templateWord->setValue('Modalidad', $fila['Modalidad'] ?? "N/A");
    $templateWord->setValue('Finicio', $fila['Finicio'] ?? "N/A");
    $templateWord->setValue('Fterminacion', $fila['Fterminacion'] ?? "N/A");
    $templateWord->setValue('Actividades', $fila['Actividades'] ?? "N/A");
    $templateWord->setValue('Ciudad', $fila['Ciudad'] ?? "N/A");
    $templateWord->setValue('Dia', date("d"));
    $templateWord->setValue('Mes', date("m"));
    $templateWord->setValue('Year', date("Y"));

    // Guardar el archivo .docx temporalmente
    $tempDocPath = 'temp.docx';
    $templateWord->saveAs($tempDocPath);

    // Cargar el archivo .docx y convertirlo a HTML
    $phpWord = IOFactory::load($tempDocPath);
    $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    ob_start();
    $htmlWriter->save('php://output');
    $html = ob_get_clean();

    // Verificar el contenido HTML
    if (empty($html)) {
        die("Error: El contenido HTML está vacío.");
    }

    // Crear el PDF usando mPDF y el contenido HTML
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);

    // Configurar encabezados para mostrar el PDF en el navegador
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=Hoja_Llena.pdf");

    // Mostrar el PDF en el navegador
    $mpdf->Output('Hoja_Llena.pdf', 'D');

    // Eliminar el archivo temporal .docx si ya no es necesario
    unlink($tempDocPath);
} else {
    echo "No se encontraron datos para el ID especificado.";
}

$conexion->close();
?>
