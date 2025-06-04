<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Inicializar la respuesta
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['inputNo_Control'], $_POST['inputUser'], $_POST['inputPassword'], $_POST['Tipo_Usuario'], $_POST['inputEstatus'])) {
        $no_control = trim($_POST['inputNo_Control']);
        $usuario = trim($_POST['inputUser']);
        $password = password_hash(trim($_POST['inputPassword']), PASSWORD_DEFAULT);
        $tipo_usuario = trim($_POST['Tipo_Usuario']);
        $estatus = trim($_POST['inputEstatus']);

        try {
            // Validar si ya existe un usuario con ese nombre
            $check_sql = "SELECT Usuario FROM cuenta WHERE Usuario = ?";
            $check_stmt = $enlace->prepare($check_sql);
            $check_stmt->bind_param("s", $usuario);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $response = [
                    "status" => "error",
                    "message" => "El nombre de usuario ya está registrado. Por favor, elige otro."
                ];
            } else {
                // Insertar los datos en la tabla cuenta
                $insert_sql = "INSERT INTO cuenta (Usuario, Password, Estatus, Tipo_Usuario, Id_Alumno) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $enlace->prepare($insert_sql);
                $insert_stmt->bind_param("sssss", $usuario, $password, $estatus, $tipo_usuario, $no_control);

                if ($insert_stmt->execute()) {
                    $response = [
                        "status" => "success",
                        "message" => "Cuenta creada exitosamente."
                    ];
                } else {
                    $response = [
                        "status" => "error",
                        "message" => "Error al crear la cuenta: " . $insert_stmt->error
                    ];
                }
                $insert_stmt->close();
            }
            $check_stmt->close();
        } catch (Exception $e) {
            $response = [
                "status" => "error",
                "message" => "Error al procesar el registro: " . $e->getMessage()
            ];
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "Faltan datos en el formulario."
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Método de solicitud no permitido."
    ];
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json'); // Asegúrate de establecer el tipo de contenido
echo json_encode($response);

// Cerrar la conexión a la base de datos
$enlace->close();
?>
