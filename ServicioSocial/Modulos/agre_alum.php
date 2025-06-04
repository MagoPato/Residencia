<?php
// Usar ruta y el nombre relativo para incluir el archivo de conexión
include('conexion.php');
header('Content-Type: application/json');

// Procesar el formulario solo si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario y sanitizarlos
    $apellido_paterno = mysqli_real_escape_string($enlace, $_POST['apellido_paterno']);
    $apellido_materno = mysqli_real_escape_string($enlace, $_POST['apellido_materno']);
    $nombres = mysqli_real_escape_string($enlace, $_POST['nombre']);
    $genero = mysqli_real_escape_string($enlace, $_POST['Genero']);
    $telefono = mysqli_real_escape_string($enlace, $_POST['telefono']);
    $numero_control = mysqli_real_escape_string($enlace, $_POST['numero_control']);
    $semestre = mysqli_real_escape_string($enlace, $_POST['Semestre']);
    $turno = mysqli_real_escape_string($enlace, $_POST['Turno']);
    $modalidad = mysqli_real_escape_string($enlace, $_POST['Modalidad']);
    $correo = mysqli_real_escape_string($enlace, $_POST['Correo']);
    $estatus = mysqli_real_escape_string($enlace, $_POST['Estatus']);
    $carrera = mysqli_real_escape_string($enlace, $_POST['carrera']);
    $créditos = mysqli_real_escape_string($enlace, $_POST['Créditos']);    

    // Datos de la tabla direccion
    $calle = mysqli_real_escape_string($enlace, $_POST['direccion_calle']);
    $numero_externo = mysqli_real_escape_string($enlace, $_POST['direccion_Numero_Externo']);
    $numero_interno = mysqli_real_escape_string($enlace, $_POST['direccion_Numero_Interno']);
    $colonia = mysqli_real_escape_string($enlace, $_POST['Colonia']);
    $codigo_postal = mysqli_real_escape_string($enlace, $_POST['Codigo_Postal']);
    $municipio = mysqli_real_escape_string($enlace, $_POST['direccion_municipio']);
    $estado = mysqli_real_escape_string($enlace, $_POST['direccion_estado']);
    
    $enlace->begin_transaction();
    try {
        // Consulta para insertar en la tabla 'direccion'
        $sql1 = "INSERT INTO direccion (Calle, NumeroExt, NumeroInt, Colonia, CP, Ciudad, Estado)
                 VALUES ('$calle', '$numero_externo', '$numero_interno', '$colonia', '$codigo_postal', '$municipio', '$estado')";
        
        if ($enlace->query($sql1) === TRUE) {
            // Capturar el último ID insertado en la tabla direccion
            $id_direccion = $enlace->insert_id;

            // Consulta para insertar en la tabla 'alumno'
           $sql2 = "INSERT INTO alumno (Apellido_P, Apellido_M, Nombre, Telefono, No_Control, Semestre, Modalidad, Correo, Turno, Estatus, Genero, Carrera, Id_Direccion, Créditos)
         VALUES ('$apellido_paterno', '$apellido_materno', '$nombres', '$telefono', '$numero_control', '$semestre', '$modalidad', '$correo', '$turno', '$estatus', '$genero', '$carrera', '$id_direccion', '$Créditos')";
            
            if ($enlace->query($sql2) === TRUE) {
                $enlace->commit();
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Registro completado exitosamente.'
                ]);
            } else {
                throw new Exception("Error al insertar en la tabla Alumno: " . $enlace->error);
            }
        } else {
            throw new Exception("Error al insertar en la tabla Direccion: " . $enlace->error);
        }
    } catch (Exception $e) {
        // Revertir la transacción
        $enlace->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    // Cerrar la conexión
    $enlace->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido.'
    ]);
}
?>
