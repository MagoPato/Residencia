<?php
// es la conexion a la base de datos
//include 'Modulos/conexion.php';
//$conn->close();

// conexion.php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "servicio_social"; // Variable correcta para la base de datos

// Crear conexión
$enlace = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if ($enlace->connect_error) {
    die("Conexión fallida: " . $enlace->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta para verificar el usuario y la contraseña
    $query = "SELECT Id, Tipo_Usuario, Estatus FROM cuenta WHERE usuario = ? AND password = ?";
    $stmt = $enlace->prepare($query);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado, verificar estatus y tipo
        $row = $result->fetch_assoc();
        if ($row['Estatus'] != 'A') {
            // Usuario inhabilitado
            $error = "Usuario inhabilitado.";
        } else {
            // Redirigir según el tipo de usuario
            if ($row['Tipo_Usuario'] == 'A') {
                header("Location: IndexAlum.php"); // Redirige al área de administrador
                exit();
            } elseif ($row['Tipo_Usuario'] == 'U') {
                header("Location: index.php"); // Redirige al área de alumno
                exit();
            } else {
                $error = "Tipo de usuario no válido.";
            }
        }
    } else {
        // Credenciales incorrectas
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SB Admin</title>
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #11366A;
        }
        .card {
            margin-top: 5%;
            margin-bottom: 5%;
            background-color: #8A110F;
            color: black;
        }
        .card-header {
            background-color: white;
            color: black;
        }
        .btn {
            background-color: #11366A;
            color: white;
            transition: 0.3s;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #07244A;
            color: #f2f2f2;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        a:hover {
            color: hotpink;
        }
        a {
            color: #bfc9ca;
        }
    </style>
</head>

<body>
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
                                            <input class="form-control" id="inputNumeroControl" name="usuario" type="text" placeholder="123456789" required>
                                            <label for="inputNumeroControl">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required>
                                            <label for="inputPassword">Contraseña</label>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Login</button>
                                            <a class="forgot-password" href="ResetPass.php">¿Olvidaste tu contraseña?</a>
                                        </div>
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
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
    <script src="js/scripts.js"></script>
</body>

</html>