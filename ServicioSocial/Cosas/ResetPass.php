<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Reestablecer Contraseña</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>   
    <style>
        body {
            background-color: #11366A;
        }
        .card {
            margin-top: 5%;
            margin-bottom: 5%;
            background-color: #bfc9ca;
            color: black;
        }
        .card-header {
            background-color: #8A110F;
            color: white;
        }
        .btn {
            background-color: #000;
            color: white;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #07244A;
            color: pink;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        a:hover {
            color: hotpink; 
        }
        a {
            color: blue;
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
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Recuperar Contraseña</h3></div>
                                <div class="card-body">
                                    <div class="small mb-3 text-muted">Ingrese su dirección de correo electrónico y le enviaremos un enlace para restablecer su contraseña.</div>
                                    <form>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" />
                                            <label for="inputEmail">Dirección Email</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="login.php">Regresar al Login</a>
                                            <a class="btn btn-primary" href="restablecer.php">Reestablecer Contraseña</a>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.php">Necesitas una Cuenta? Registrate!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include 'Modulos/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
