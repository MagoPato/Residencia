<?php
session_start();  // Inicia la sesión

// Eliminar las variables específicas de sesión
unset($_SESSION['ultimo_acceso']);  // Elimina la variable de última actividad
unset($_SESSION['no_control']);     // Elimina la variable de control de usuario

// Eliminar todas las demás variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Eliminar la cookie de sesión si se usa
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params['path'], 
        $params['domain'], 
        $params['secure'], 
        $params['httponly']
    );
}

// Eliminar las cookies 'user_id' y 'remember_me' (si existen)
setcookie("user_id", "", time() - 3600, "/", "", true, true); // Expirar la cookie 'user_id'
setcookie("remember_me", "", time() - 3600, "/", "", true, true); // Expirar la cookie 'remember_me'

// Prevenir que la página se almacene en caché después del logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirigir al usuario al login o página principal
header("Location: https://" . $_SERVER['HTTP_HOST']);
exit();
?>
