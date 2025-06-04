<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos enviados desde el formulario
    $fechaInicio = mysqli_real_escape_string($enlace, $_POST['FechaIni']);
    $fechaFinal = mysqli_real_escape_string($enlace, $_POST['FechaFin']);

    // Consulta SQL para insertar los datos
    $sql1 = "INSERT INTO fecha_registro (Fecha_In, Fecha_Fin)
             VALUES ('$fechaInicio', '$fechaFinal')";

    if ($enlace->query($sql1) === TRUE) {
        echo "<script>
                alert('Fechas registradas exitosamente.');
                window.location.href = '/residencia/dev/Incluye/admin/FechaReg.php';
              </script>";
    } else {
        echo "Error al insertar en la tabla fecha_registro: " . $enlace->error;
    }
}
// Cerrar la conexión
$enlace->close();
?>
