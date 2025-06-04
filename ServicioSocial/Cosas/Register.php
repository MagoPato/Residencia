<?php
include 'Modulos/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios - Servicio Social</title>
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos personalizados */
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
                                    <h3 class="text-center font-weight-light my-4">Registro de Usuarios Servicio Social</h3>
                                </div>
                                <div class="card-body">
                                    <form action="/residencia/dev/modulos/Registro_usuario.php" method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUser" name="usuario" type="text" placeholder="Usuario" required>
                                            <label for="inputUser">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Contraseña" required>
                                            <label for="inputPassword">Contraseña</label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="Tipo" name="Tipo" aria-label="Tipo de Usuario" required>
                                                    <option selected disabled>Seleccione Tipo de Usuario</option>
                                                    <option value="U">Alumno</option>
                                                </select>
                                                <label for="Tipo">Tipo de Usuario</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputEstatus" name="inputEstatus" aria-label="Estado de Usuario" required>
                                                    <option selected disabled>Estado de Usuario</option>
                                                    <option value="A">Activo (Por registro)</option>
                                                </select>
                                                <label for="inputEstatus">Estado de Usuario</label>
                                            </div>
                                        </div>
                                        <!-- Botón de envío de formulario -->
                                        <div class="d-grid mb-3">
                                            <button type="submit" class="btn btn-primary">Registrarse</button>
                                        </div>
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