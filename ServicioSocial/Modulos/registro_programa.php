<?php
include('conexion.php');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos de la tabla Dependencia
    $nombre_dependencia = mysqli_real_escape_string($enlace, $_POST['nombre_dependencia']);
    $Nombre_responsable = mysqli_real_escape_string($enlace, $_POST['Nombre_responsable']);
    $puesto_responsable = mysqli_real_escape_string($enlace, $_POST['puesto_responsable']);
    $Apellido_responsable = mysqli_real_escape_string($enlace, $_POST['Apellido_responsable']);

    // Datos de la tabla Servicio
    $nombre_programa = mysqli_real_escape_string($enlace, $_POST['nombre_programa']);
    $actividades = mysqli_real_escape_string($enlace, $_POST['actividades']);
    $departamento = mysqli_real_escape_string($enlace, $_POST['departamento']);
    $tipo_servicio = mysqli_real_escape_string($enlace, $_POST['tipo_servicio']);

    // Datos de la tabla Responsable
    $Nombre_encargado  = mysqli_real_escape_string($enlace, $_POST['EncargadoNom']);
    $Puesto_encargado = mysqli_real_escape_string($enlace, $_POST['PuestoEn']);

    // Iniciar transacción
    $enlace->begin_transaction();

    try {
        // Insertar en 'dependencia'
        $sql1 = "INSERT INTO dependencia (Nombre, Encargado_N, Encargado_A, Puesto_En)
                 VALUES ('$nombre_dependencia', '$Nombre_responsable', '$Apellido_responsable', '$puesto_responsable')";

        if ($enlace->query($sql1) === TRUE) {
            $id_dependencia = $enlace->insert_id;

            // Insertar en 'responsable' y obtener el ID
            $sql3 = "INSERT INTO responsable (nombre, puesto)
                     VALUES ('$Nombre_encargado', '$Puesto_encargado')";

            if ($enlace->query($sql3) === TRUE) {
                $id_responsable = $enlace->insert_id;

                // Insertar en 'servicio' incluyendo el ID de 'responsable'
                $sql2 = "INSERT INTO servicio (Programa, Actividades, Departamento, Tipo, Id_Dependencia, Id_Responsable)
                         VALUES ('$nombre_programa', '$actividades', '$departamento', '$tipo_servicio', '$id_dependencia', '$id_responsable')";

                if ($enlace->query($sql2) === TRUE) {
                    $enlace->commit();
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Registro completado exitosamente.'
                    ]);
                } else {
                    throw new Exception("Error al insertar en la tabla servicio: " . $enlace->error);
                }
            } else {
                throw new Exception("Error al insertar en la tabla responsable: " . $enlace->error);
            }
        } else {
            throw new Exception("Error al insertar en la tabla dependencia: " . $enlace->error);
        }
    } catch (Exception $e) {
        // Revertir la transacción
        $enlace->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    $enlace->close();
}
