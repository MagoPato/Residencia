<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos enviados desde el formulario
    $numreporte = mysqli_real_escape_string($enlace, $_POST['NumReport']);
    $horahchs = mysqli_real_escape_string($enlace, $_POST['HorasHchs']);
    $horasacum = mysqli_real_escape_string($enlace, $_POST['HorasAcm']);

    // Consulta SQL para insertar los datos
    $sql1 = "INSERT INTO reporte (No_Reporte, Horas_Rep, Horas_Acu)
             VALUES ('$fechaInicio', '$fechaFinal')";

    // Ejecutar la consulta
    if ($enlace->query($sql1) === TRUE) {
        echo "<script>
                alert('Fechas registradas exitosamente.');
                window.location.href = '/residencia/dev/Incluye/alumno/GenerarReporte.php';
              </script>";
    } else {
        echo "Error al insertar en la tabla fecha_registro: " . $enlace->error;
    }
}
// Cerrar la conexión
$enlace->close();
?>
