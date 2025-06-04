<?php
session_start();

// Redirigir a HTTPS si no está usando HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

// Incluir la conexión a la base de datos
include('Modulos/conexion.php');

// Si el usuario ya está logueado, redirigirlo
if (isset($_SESSION['usuario_id'])) {
    redirectByUserType($_SESSION['tipo_usuario']);
    exit();
}

// Función para redirigir por tipo de usuario
function redirectByUserType($tipoUsuario) {
    switch ($tipoUsuario) {
        case 'A':
            header("Location: Incluye/panel/IndexAdmin.php");
            break;
        case 'U':
            header("Location: Incluye/alumno/IndexAlum.php");
            break;
        default:
            header("Location: index.php");
            break;
    }
    exit();
}

// Inicializar variables
$usuario = '';
$password = '';
$error = '';

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($enlace, $_POST['inputUser'] ?? '');
    $password = $_POST['inputPassword'] ?? '';

    if ($usuario && $password) {
        $query = "
            SELECT c.Id, c.Tipo_Usuario, c.Estatus, c.Password, a.No_Control
            FROM cuenta c
            JOIN alumno a ON c.Id_Alumno = a.No_Control
            WHERE c.Usuario = ?
        ";
        $stmt = $enlace->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['Password'])) {
                if ($row['Estatus'] != 'A') {
                    $error = "Usuario inhabilitado.";
                } else {
                    $_SESSION['usuario_id'] = $row['Id'];
                    $_SESSION['tipo_usuario'] = $row['Tipo_Usuario'];
                    $_SESSION['no_control'] = $row['No_Control'];
                    $_SESSION['ultimo_acceso'] = time();

                    redirectByUserType($row['Tipo_Usuario']);
                }
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }

        $stmt->close();
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}

$enlace->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  	<title>Login - Servicio Social</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/css/styles.css" rel="stylesheet">
    <link href="/css/estilos-interfaz.css" rel="stylesheet">
    <link href="/css/Login.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>
        <style>
body {
  background-image: url('img/SS.png') !important;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center 11%; /* Desplaza la imagen hacia abajo */
  min-height: 80vh;
}


  </style>
</head>

<body>
         <?php
    // contenido.php
    include 'Incluye/barra.php';
    ?>   
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-lg rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login Servicio Social</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUser" name="inputUser" type="text" placeholder="123456789" required>
                                            <label for="inputUser">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="inputPassword" type="password" placeholder="Password" required>
                                            <label for="inputPassword">Contraseña</label>
                                        </div>
                                        <div class="d-grid mb-3">
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>

                                        <?php if (!empty($error)): ?>
                                            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include 'Modulos/footer.php'; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
