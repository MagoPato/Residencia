<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $apellido_paterno = mysqli_real_escape_string($enlace, $_POST['inputApellidoPaterno']);
    $apellido_materno = mysqli_real_escape_string($enlace, $_POST['inputApellidoMaterno']);
    $nombres = mysqli_real_escape_string($enlace, $_POST['inputFirstName']);
    $genero = mysqli_real_escape_string($enlace, $_POST['inputGener']);
    $telefono = mysqli_real_escape_string($enlace, $_POST['inputTelefono']);
    $numero_control = mysqli_real_escape_string($enlace, $_POST['inputStudentID']);
    $semestre = mysqli_real_escape_string($enlace, $_POST['inputSemester']);
    $turno = mysqli_real_escape_string($enlace, $_POST['inputTurn']);
    
    $calle = mysqli_real_escape_string($enlace, $_POST['inputStreet']);
    $numero_externo = mysqli_real_escape_string($enlace, $_POST['inputExternalNumber']);
    $numero_interno = mysqli_real_escape_string($enlace, $_POST['inputInternalNumber']);
    $colonia = mysqli_real_escape_string($enlace, $_POST['inputColonia']);
    $codigo_postal = mysqli_real_escape_string($enlace, $_POST['inputPostalCode']);
    $municipio = mysqli_real_escape_string($enlace, $_POST['inputCity']);
    $estado = mysqli_real_escape_string($enlace, $_POST['inputState']);
    
    $modalidad = mysqli_real_escape_string($enlace, $_POST['inputModalidad']);
    $correo = mysqli_real_escape_string($enlace, $_POST['inputCorreo']);
    $estatus = mysqli_real_escape_string($enlace, $_POST['inputEstatus']);
    $carrera = mysqli_real_escape_string($enlace, $_POST['inputMajor']);
    $creditos = mysqli_real_escape_string($enlace, $_POST['inputCreditos']);

    // Verificar si el número de control ya existe
    $verificar = "SELECT * FROM alumno WHERE No_Control = '$numero_control'";
    $resultado = $enlace->query($verificar);

    if ($resultado && $resultado->num_rows > 0) {
        echo "<script>
            alert('El número de control ya existe. Por favor ingresa uno diferente.');
            window.location.href = '../forms/Formulario.php'; // Cambia esta ruta si es necesario
        </script>";
        exit;
    }

    $sql2 = "INSERT INTO direccion (Calle, NumeroExt, NumeroInt, Colonia, CP, Ciudad, Estado)
             VALUES ('$calle', '$numero_externo', '$numero_interno', '$colonia', '$codigo_postal', '$municipio', '$estado')";

    if ($enlace->query($sql2) === TRUE) {
        $id_direccion = $enlace->insert_id;

        $sql1 = "INSERT INTO alumno (Apellido_P, Apellido_M, Nombre, Telefono, No_Control, Semestre, Modalidad, Correo, Turno, Estatus, Genero, Carrera, Créditos, Id_Direccion)
                 VALUES ('$apellido_paterno', '$apellido_materno', '$nombres', '$telefono', '$numero_control', '$semestre', '$modalidad', '$correo', '$turno', '$estatus', '$genero', '$carrera', '$creditos', '$id_direccion')";

        if ($enlace->query($sql1) === TRUE) {
            session_start();
            session_destroy();
            echo "<script>
                window.location.href = '../Incluye/informacion.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al registrar alumno.');
                window.location.href = '../forms/Formulario.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Error al registrar dirección.');
            window.location.href = '../forms/Formulario.php';
        </script>";
    }

    $enlace->close();
}
?>
