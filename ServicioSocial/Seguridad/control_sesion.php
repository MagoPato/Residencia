<?php
session_start();  // Inicia la sesión

// Control de inactividad (15 minutos = 900 segundos)
$tiempoMaxInactividad = 15 * 60;

// Verifica si el tiempo de inactividad ha superado el límite
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempoInactivo = time() - $_SESSION['ultimo_acceso'];

    if ($tiempoInactivo > $tiempoMaxInactividad) {
        session_unset();     // Borra variables de sesión
        session_destroy();   // Destruye la sesión
        header("Location: https://" . $_SERVER['HTTP_HOST']); // Redirigir al login
        exit();
    }
}

// Actualiza el tiempo de última actividad
$_SESSION['ultimo_acceso'] = time();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['no_control'])) {
    // Opcional: Registrar intento de acceso no autorizado
    error_log("Intento de acceso no autorizado el " . date('Y-m-d H:i:s') . " desde IP: " . $_SERVER['REMOTE_ADDR']);

    // Redirigir al usuario al login si no está autenticado
    header("Location: https://" . $_SERVER['HTTP_HOST']);
    exit();
}
?>
