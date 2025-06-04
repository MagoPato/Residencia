<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Social- Alumno</title>
    <link href="../../css/styles.css" rel="stylesheet" />
    <style>
        body {
            background-color: #7E1710;
        }

        .service-card {
            margin-bottom: 30px;

        }

        .pagination {
            margin-top: 12px;
        }

        .jumbotron {
            background-color: #007bff;
            color: #fff;
            padding: 1rem 1rem;
            margin-bottom: 4%;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="jumbotron jumbotron-fluid">
            <div class="container text-center">
                <h1 class="display-4">Servicio Social</h1>
            </div>
        </div>
        <div class="row">
            <?php
            $servicios_sociales = array(
                array("Centro Comunitario", "Horario: 8:00 AM - 5:00 PM", "Ofrece actividades y talleres para la comunidad."),
                array("Comedor Social", "Horario: 11:00 AM - 2:00 PM", "Proporciona alimentos a personas necesitadas."),
                array("Centro de Apoyo Familiar", "Horario: 9:00 AM - 6:00 PM", "Brinda asistencia y orientación a familias."),
                array("Taller de Reciclaje", "Horario: 10:00 AM - 4:00 PM", "Enseña técnicas de reciclaje y sostenibilidad."),
                array("Asistencia Legal Gratuita", "Horario: 8:30 AM - 4:30 PM", "Ofrece asesoramiento legal sin costo."),
                array("Clases de Idiomas", "Horario: 6:00 PM - 9:00 PM", "Imparte clases de idiomas para la comunidad."),
                array("Programa de Voluntariado", "Horario: 10:00 AM - 3:00 PM", "Permite participar en actividades solidarias."),
                array("Centro de Orientación Juvenil", "Horario: 9:00 AM - 7:00 PM", "Apoya a jóvenes en situación de riesgo."),
                array("Grupo de Apoyo Emocional", "Horario: 3:00 PM - 8:00 PM", "Proporciona apoyo psicológico a personas."),
                array("Banco de Alimentos", "Horario: 7:00 AM - 6:00 PM", "Distribuye alimentos a familias necesitadas."),
                array("Centro de Recursos para la Tercera Edad", "Horario: 10:00 AM - 4:00 PM", "Ofrece actividades recreativas para personas mayores."),
                array("Programa de Alfabetización", "Horario: 8:00 AM - 12:00 PM", "Enseña a leer y escribir a adultos."),
                array("Apoyo a Personas con Discapacidad", "Horario: 9:00 AM - 5:00 PM", "Proporciona asistencia y recursos a personas con discapacidad."),
                array("Campañas de Vacunación", "Horario: 8:00 AM - 6:00 PM", "Realiza campañas de vacunación para la comunidad."),
                array("Talleres de Arte y Creatividad", "Horario: 2:00 PM - 7:00 PM", "Imparte clases y talleres de arte para niños."),
                array("Asesoramiento Financiero", "Horario: 10:00 AM - 4:00 PM", "Brinda asesoramiento sobre finanzas personales."),
                array("Centro de Asistencia Psicológica", "Horario: 9:00 AM - 6:00 PM", "Ofrece terapia y asesoramiento psicológico."),
                array("Biblioteca Comunitaria", "Horario: 9:00 AM - 8:00 PM", "Proporciona acceso a libros y recursos educativos.")
            );

            // Número de servicios por página
            $servicios_por_pagina = 6;

            // Página actual, si no se especifica, la primera página por defecto
            $pagina_actual = isset($_GET['page']) ? $_GET['page'] : 1;

            // Índice de inicio del primer servicio en la página actual
            $indice_inicio = ($pagina_actual - 1) * $servicios_por_pagina;

            // Índice de fin del último servicio en la página actual
            $indice_fin = $indice_inicio + $servicios_por_pagina;

            // Subarray de servicios para la página actual
            $servicios_pagina = array_slice($servicios_sociales, $indice_inicio, $servicios_por_pagina);

            foreach ($servicios_pagina as $index => $servicio) {
            ?>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $servicio[0]; ?></h5>
                            <p class="card-text"><?php echo $servicio[1]; ?></p>
                            <p class="card-text"><?php echo $servicio[2]; ?></p>
                            <button class="btn btn-primary">Seleccionar Servicio</button>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="pagination justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                // Calcular el número total de páginas
                $num_paginas = ceil(count($servicios_sociales) / $servicios_por_pagina);
                for ($i = 1; $i <= $num_paginas; $i++) {
                ?>
                    <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </div>

    <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center small">
                    <div class="text-muted">
                        Copyright &copy; Your Website 2023
                    </div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                    <div class="text-muted">
                        Designed by: David Escamilla, Sofia Ramos, Devany Rojas, and Jose Covarubias
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>