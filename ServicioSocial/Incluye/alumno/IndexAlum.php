<?php
session_start();  // Inicia la sesión

include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos

include '../../Seguridad/control_sesion.php'; 


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Panel de Estudiante" />
    <meta name="author" content="Servicio Social" />
    <title>Servicio Social - Alumno</title>

    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />

    <!-- Iconos -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
   
</head>

<body class="sb-nav-fixed">
    
    <?php include 'barra.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 text-center">¡Bienvenido al Panel de Estudiante!</h1>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-lg mb-4 border-0">
                            <div class="card-body">
                                <h4 class="card-title text-primary text-center mb-4">¡Accede a tu Información!</h4>
                                <p class="card-text text-center text-muted fs-5">
                                    Bienvenido al panel de Estudiante, tu plataforma personal para acceder a información relevante sobre tu servicio social.
                                </p>
                                <p class="card-text text-center text-muted fs-5">
                                    Nuestra misión es brindarte una experiencia transparente y conveniente, facilitando tu participación en el servicio social y ayudándote a alcanzar tus objetivos académicos y personales.
                                </p>
                                <div class="card-footer bg-light d-flex align-items-center justify-content-center">
                                    <small class="fw-bold">
                                        ¡Explora todas las funciones disponibles y maximiza tu experiencia en el servicio social!
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php
        // Incluyendo pie de página
        include '../../Modulos/footer.php';
        ?>
    </div>

    <!-- Scripts -->
<script src="../../js/scripts.js"></script>
<script src="../../js/datatables-simple-demo.js"></script>



</body>

</html>
