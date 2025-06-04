<?php
require '../dev/vendor/autoload.php'; // Asegúrate de incluir el autoloader de Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Crear un nuevo objeto de hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Escribir datos en la hoja de cálculo
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Correo');

    $sheet->setCellValue('A2', '1');
    $sheet->setCellValue('B2', 'Juan Pérez');
    $sheet->setCellValue('C2', 'juan.perez@example.com');

    $sheet->setCellValue('A3', '2');
    $sheet->setCellValue('B3', 'Ana López');
    $sheet->setCellValue('C3', 'ana.lopez@example.com');

    // Crear un archivo XLSX
    $writer = new Xlsx($spreadsheet);

    // Guardar el archivo en el servidor
    $fileName = 'archivo_ejemplo.xlsx';
    $writer->save($fileName);

    echo "Archivo '$fileName' generado exitosamente.";
} catch (Exception $e) {
    echo "Error al generar el archivo: " . $e->getMessage();
}
?>