<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Id_Servicio = isset($_POST['Id_Servicio']) ? $_POST['Id_Servicio'] : null;
    $Cupo = isset($_POST['Cupo']) ? $_POST['Cupo'] : null;
    $horario_servicio = isset($_POST['horario_servicio']) ? $_POST['horario_servicio'] : null;
    $carreras = isset($_POST['Carrera']) ? $_POST['Carrera'] : [];

    if (!empty($carreras)) {
        $carreras_str = implode('/', $carreras);
        $stmt = $enlace->prepare("INSERT INTO cupos (Id_Servicio, Carrera, Cupo, Horario) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("isis", $Id_Servicio, $carreras_str, $Cupo, $horario_servicio);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Cupo agregado exitosamente."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al agregar el cupo: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Error en la preparación de la consulta."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se seleccionó ninguna carrera."]);
    }
}

mysqli_close($enlace);
