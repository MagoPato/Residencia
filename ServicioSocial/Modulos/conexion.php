<?php
// Función para cargar las variables del archivo .env
function loadEnv($file)
{
    if (!file_exists($file)) {
        die("❌ El archivo .env no existe.");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;

        list($key, $value) = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
            die("❌ Clave de entorno inválida detectada: $key");
        }

        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Cargar variables desde el archivo .env
loadEnv(__DIR__ . '/../.env');

// Obtener datos de conexión
$servername = getenv("DB_HOST");
$username   = getenv("DB_USER");
$password   = getenv("DB_PASS");
$dbname     = getenv("DB_NAME");

// Conectar a la base de datos
$enlace = mysqli_connect($servername, $username, $password, $dbname);

if ($enlace->connect_error) {
    die("Conexión fallida: " . $enlace->connect_error);
}

