<?php
header('Content-Type: application/json');

// Conexión a la base de datos
include '../../Modulos/conexion.php';

// Obtener el ID del alumno
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Consulta a la base de datos
    $sql = "SELECT Nombre, Apellido_P, Apellido_M, No_Control, Carrera, Correo, Telefono FROM alumnos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del alumno
        $alumno = $result->fetch_assoc();
        echo json_encode($alumno);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron datos para el ID proporcionado.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
}

// Cerrar la conexión
$conn->close();
