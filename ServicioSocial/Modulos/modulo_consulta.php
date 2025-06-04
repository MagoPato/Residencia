<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include('conexion.php');

// Verificar conexión a la base de datos
if (!$enlace) {
    die(json_encode([
        "status" => "error",
        "message" => "Error al conectar a la base de datos: " . mysqli_connect_error()
    ]));
}

try {
    // Realizar la consulta de la tabla 'alumno' incluyendo la columna Créditos
    $query = "SELECT No_Control, Nombre, Apellido_P, Apellido_M, 
                     Telefono, Semestre, Correo, 
                     Turno, Estatus, Genero, Carrera, Créditos
              FROM alumno";
    $resultado = mysqli_query($enlace, $query);
    
    if (!$resultado) {
        throw new Exception("Error al ejecutar la consulta: " . mysqli_error($enlace));
    }
    
    // Verificar si se encontraron resultados
    if (mysqli_num_rows($resultado) > 0) {
        // Construir las filas de la tabla
        $output = '';
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Sanitizar los datos antes de mostrarlos para evitar XSS
            $output .= '<tr>';
            $output .= '<td>' . htmlspecialchars($fila['No_Control'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Nombre'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Apellido_P'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Apellido_M'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Telefono'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Semestre'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Correo'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Turno'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Estatus'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Genero'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Carrera'] ?? '') . '</td>';
            $output .= '<td>' . htmlspecialchars($fila['Créditos'] ?? '0') . '</td>';
            $output .= '<td>';
            $output .= '<a href="crear_cuenta.php?id=' . htmlspecialchars($fila['No_Control'] ?? '') . '" class="btn btn-warning btn-sm me-1">Aceptar</a>';
            $output .= '<a href="EliminarEstudiante.php?id=' . htmlspecialchars($fila['No_Control'] ?? '') . '" class="btn btn-danger btn-sm">Rechazar</a>';
            $output .= '</td>';
            $output .= '</tr>';
        }
        
        // Enviar los resultados
        echo $output;
    } else {
        // En caso de no haber resultados
        echo '<tr><td colspan="13" class="text-center text-danger">No se encontraron resultados.</td></tr>';
    }
    
} catch (Exception $e) {
    // En caso de error, enviar un mensaje de error
    die(json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]));
}
?>