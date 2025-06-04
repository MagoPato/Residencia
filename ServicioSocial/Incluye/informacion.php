<?php
session_start();  // Inicia la sesión
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Panel de Estudiante" />
    <meta name="author" content="Servicio Social" />
    <title>Servicio Social - Estado de Solicitud</title>
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/estilos-interfaz.css" rel="stylesheet" />
    <!-- Iconos -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
   
</head>
<body class="sb-nav-fixed">
    
    <?php include 'barra.php'; ?>
    <br>
    <br>    
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 text-center">Estado de tu Solicitud</h1>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-lg mb-4 border-0">
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                                    <h4 class="card-title text-warning">Solicitud en Proceso</h4>
                                </div>
                                
                                <div class="alert alert-info text-center" role="alert">
                                    <h5 class="alert-heading mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Información Importante
                                    </h5>
                                    <p class="mb-3 fs-6">
                                        <strong>En este momento no puedes acceder a todas las funciones del sistema.</strong> 
                                        Tu solicitud de servicio social está siendo evaluada por el área correspondiente.
                                    </p>
                                    <hr>
                                    <p class="mb-3 fs-6">
                                        <strong>¿Qué hacer mientras esperas?</strong><br>
                                        Mantente atento a las notificaciones oficiales. Una vez que tu solicitud sea 
                                        ACEPTADA, tendrás acceso completo 
                                        a todas las funciones del sistema.
                                    </p>
                                </div>

                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-key me-2"></i>
                                            Información de Acceso
                                        </h6>
                                        <p class="card-text mb-2">
                                            Una vez que recibas la notificación de aceptación, tus credenciales de acceso serán:
                                        </p>
                                        <ul class="list-unstyled ms-3">
                                            <li><strong>Usuario:</strong> Tu número de control</li>
                                            <li><strong>Contraseña:</strong> Tu número de control</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Importante
                                    </h6>
                                    <p class="mb-0 small">
                                        Si después de varios días no puedes ingresar con tus credenciales, 
                                        o si no recibes notificación alguna, es posible que tu solicitud haya sido 
                                        <strong>rechazada debido a créditos insuficientes</strong> u otros requisitos académicos.
                                    </p>
                                </div>

                                <div class="text-center mt-4">
                                    <p class="text-muted fs-6">
                                        <i class="fas fa-clock me-2"></i>
                                        Tiempo estimado de respuesta: 3-5 días hábiles
                                    </p>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-light text-center">
                                <small class="text-muted">
                                    <i class="fas fa-envelope me-2"></i>
                                    Revisa tu correo institucional regularmente para recibir actualizaciones sobre tu solicitud
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
        // Incluyendo pie de página
        include '../Modulos/footer.php';
        ?>
    </div>
    <!-- Scripts -->
<script src="../js/scripts.js"></script>
<script src="../js/datatables-simple-demo.js"></script>
</body>
</html>