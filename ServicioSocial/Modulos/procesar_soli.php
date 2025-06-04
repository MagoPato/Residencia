<?php
session_start();
include '../../Modulos/conexion.php'; // Conectar a la base de datos

if (!isset($_SESSION['no_control'])) {
    echo json_encode(["status" => "error", "message" => "Sesión no iniciada."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_alumno = $_SESSION['no_control'];
    $id_servicio = isset($_POST['id_servicio']) ? intval($_POST['id_servicio']) : 0;
    $id_cupo = isset($_POST['id_cupo']) ? intval($_POST['id_cupo']) : 0;

    if (!$id_servicio || !$id_cupo) {
        echo json_encode(["status" => "error", "message" => "ID de servicio o cupo no recibido."]);
        exit;
    }

    try {
        $enlace->begin_transaction();

        // Obtener datos necesarios con una sola consulta
        $query = "SELECT s.Id_Dependencia, d.Id_Responsable, c.Cupo 
                  FROM servicio s
                  JOIN dependencia d ON s.Id_Dependencia = d.Id
                  JOIN cupos c ON c.Id = ?
                  WHERE s.Id = ? FOR UPDATE";

        $stmt = $enlace->prepare($query);
        $stmt->bind_param("ii", $id_cupo, $id_servicio);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Servicio o cupo no encontrado.");
        }

        $row = $result->fetch_assoc();
        $id_dependencia = $row['Id_Dependencia'];
        $id_rspnb = $row['Id_Responsable'];
        $cupo_disponible = $row['Cupo'];

        // Verificar si el alumno ya está inscrito en este servicio
        $verificar_query = "SELECT ID FROM inscripciones WHERE Id_Alumno = ? AND Id_Servicio = ?";
        $verificar_stmt = $enlace->prepare($verificar_query);
        $verificar_stmt->bind_param("si", $id_alumno, $id_servicio);
        $verificar_stmt->execute();
        $verificar_result = $verificar_stmt->get_result();

        if ($verificar_result->num_rows > 0) {
            throw new Exception("Ya estás inscrito en este servicio.");
        }

        // Verificar cupos disponibles
        if ($cupo_disponible <= 0) {
            throw new Exception("No hay cupos disponibles.");
        }

        // Insertar la inscripción
        $insert_query = "INSERT INTO inscripciones (Id_Alumno, Id_Cupo, Id_Servicio, Id_Dependencia, Id_Rspnb) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $enlace->prepare($insert_query);
        $insert_stmt->bind_param("siiii", $id_alumno, $id_cupo, $id_servicio, $id_dependencia, $id_rspnb);

        if (!$insert_stmt->execute()) {
            throw new Exception("Error al registrar inscripción.");
        }

        // Actualizar cupos disponibles
        $update_cupo_query = "UPDATE cupos SET Cupo = Cupo - 1 WHERE Id = ?";
        $update_cupo_stmt = $enlace->prepare($update_cupo_query);
        $update_cupo_stmt->bind_param("i", $id_cupo);
        $update_cupo_stmt->execute();

        $enlace->commit();
        echo json_encode(["status" => "success", "message" => "Inscripción exitosa."]);
    } catch (Exception $e) {
        $enlace->rollback();
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Acceso denegado."]);
}
?>