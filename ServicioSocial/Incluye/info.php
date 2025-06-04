<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información - Servicio Social</title>
    <link rel="shortcut icon" href="../favicon.ico">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/estilos-interfaz.css" rel="stylesheet">
    <link href="../css/Login.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .info-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 1rem;
            margin-top: 3rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #28A745;
        }
        .contacto, .atencion {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php include 'barra.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="info-container">
                    <h2 class="text-center mb-4">Información sobre el Servicio Social</h2>
                    
                    <p><strong>¿Qué es el Servicio Social?</strong></p>
                    <p>
                        Es una actividad de participación activa de los jóvenes estudiantes en la educación superior en la solución de problemas específicos de la sociedad.
                        El servicio social es obligatorio para todo estudiante para obtener el título.
                    </p>

                    <p><strong>¿Cuándo puedo realizar mi servicio social?</strong></p>
                    <p>
                        Al tener el 70% de créditos aprobados del plan de estudios, puedes inscribirte para realizar tu servicio social que consta de 480 horas.
                    </p>

                    <p><strong>¿Dónde puedes realizar el servicio social?</strong></p>
                    <p>
                        En instituciones de gobierno, educativas, instituciones ABP.
                    </p>

                    <div class="atencion">
                        
  </div>
                    <div class="contacto">
                        <p><strong>Contacto:</strong></p>
                        <ul>
                            <li>Gisela Ochoa Jacobo</li>
                            <li>Jefa de la Oficina de Servicio Social y Desarrollo Comunitario</li>
                            <li>Email: gisela.oj@nuevoleon.tecnm.mx</li>
                            <li>servicio.social@nuevoleon.tecnm.mx</li>
                            <li>Teléfono: (81) 8157 0500 Ext. 133</li>
                            <li>Ubicación: Edificio #19</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<br>
    <?php include '../Modulos/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
