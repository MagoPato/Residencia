<?php
session_start();  // Inicia la sesión

include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos

include '../../Seguridad/control_sesion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Programas de Servicio Social" />
    <meta name="author" content="Sistema de Servicio Social" />
    <title>Servicio Social - Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
         <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Barra de navegación -->
    <?php include 'barra.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <h1 class="text-center mb-5">Programas de Servicio Social</h1>

                <?php
                // Verificar fechas de registro para servicio social
                $query_fechas = "SELECT Fecha_In, Fecha_Fin FROM fecha_registro WHERE tipo = 'servicio'";
                $result_fechas = $enlace->query($query_fechas);
                
                $fecha_actual = date('Y-m-d');
                $puede_seleccionar = false;
                $mensaje_fechas = "";
                
                if ($result_fechas && $result_fechas->num_rows > 0) {
                    $fechas = $result_fechas->fetch_assoc();
                    $fecha_inicio = $fechas['Fecha_In'];
                    $fecha_fin = $fechas['Fecha_Fin'];
                    
                    // Verificar si la fecha actual está dentro del rango
                    if ($fecha_actual >= $fecha_inicio && $fecha_actual <= $fecha_fin) {
                        $puede_seleccionar = true;
                        $mensaje_fechas = "Período de selección activo hasta: " . date('d/m/Y', strtotime($fecha_fin));
                    } else if ($fecha_actual < $fecha_inicio) {
                        $mensaje_fechas = "El período de selección comenzará el: " . date('d/m/Y', strtotime($fecha_inicio));
                    } else {
                        $mensaje_fechas = "El período de selección ha finalizado. Las fechas fueron del " . 
                                        date('d/m/Y', strtotime($fecha_inicio)) . " al " . date('d/m/Y', strtotime($fecha_fin));
                    }
                } else {
                    $mensaje_fechas = "No se han establecido fechas para la selección de servicio social.";
                }
                ?>

                <!-- Mostrar información sobre las fechas -->
                <div class="alert <?php echo $puede_seleccionar ? 'alert-success' : 'alert-warning'; ?> text-center mb-4">
                    <i class="fas <?php echo $puede_seleccionar ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                    <?php echo $mensaje_fechas; ?>
                </div>

                <?php if ($puede_seleccionar) : ?>
                    <?php
                    // Número de servicios por página
                    $servicios_por_pagina = 9;

                    // Página actual (por defecto, la primera página)
                    $pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $pagina_actual = max(1, $pagina_actual); // Evitar números negativos o cero

                    // Índice de inicio para la consulta
                    $indice_inicio = ($pagina_actual - 1) * $servicios_por_pagina;

                    // Consulta SQL modificada para servicios con cupos disponibles (Cupo > 0) con paginación
                    $query = "
                        SELECT DISTINCT d.Nombre AS dependencia, d.Encargado_N, s.Programa, s.Departamento, s.Id AS id_servicio
                        FROM servicio s 
                        JOIN dependencia d ON s.Id_Dependencia = d.Id
                        JOIN cupos c ON s.Id = c.Id_Servicio
                        WHERE c.Cupo > 0
                        LIMIT ?, ?";
                    $stmt = $enlace->prepare($query);
                    $stmt->bind_param("ii", $indice_inicio, $servicios_por_pagina);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Guardar resultados
                    $servicios_sociales = [];
                    while ($row = $result->fetch_assoc()) {
                        $servicios_sociales[] = array(
                            'programa' => htmlspecialchars($row['Programa']),
                            'dependencia' => htmlspecialchars($row['dependencia']),
                            'departamento' => htmlspecialchars($row['Departamento']),
                            'id_servicio' => $row['id_servicio']
                        );
                    }

                    // Total de servicios CON CUPOS DISPONIBLES para calcular páginas correctamente
                    $query_total = "
                        SELECT COUNT(DISTINCT s.Id) AS total 
                        FROM servicio s 
                        JOIN cupos c ON s.Id = c.Id_Servicio 
                        WHERE c.Cupo > 0";
                    $total_result = $enlace->query($query_total)->fetch_assoc();
                    $total_paginas = ceil($total_result['total'] / $servicios_por_pagina);
                    ?>

                    <!-- Mostrar tarjetas de servicios -->
                    <div class="row">
                        <?php if (!empty($servicios_sociales)) : ?>
                            <?php foreach ($servicios_sociales as $servicio) : ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $servicio['programa']; ?></h5>
                                            <p class="card-text"><strong>Dependencia:</strong> <?php echo $servicio['dependencia']; ?></p>
                                            <p class="card-text"><strong>Departamento:</strong> <?php echo $servicio['departamento']; ?></p>
                                            
                                            <?php
                                            // Mostrar información de cupos disponibles
                                            $query_cupos = "SELECT SUM(Cupo) as total_cupos FROM cupos WHERE Id_Servicio = ? AND Cupo > 0";
                                            $stmt_cupos = $enlace->prepare($query_cupos);
                                            $stmt_cupos->bind_param("i", $servicio['id_servicio']);
                                            $stmt_cupos->execute();
                                            $result_cupos = $stmt_cupos->get_result();
                                            $cupos_info = $result_cupos->fetch_assoc();
                                            $cupos_disponibles = $cupos_info['total_cupos'] ?? 0;
                                            ?>
                                            
                                            <p class="card-text">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-users"></i> <?php echo $cupos_disponibles; ?> cupo(s) disponible(s)
                                                </span>
                                            </p>
                                            
                                            <form action="FichaServicio.php" method="POST">
                                                <!-- ID del servicio como campo oculto -->
                                                <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                                                <button type="submit" class="btn btn-primary">Seleccionar Servicio</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <!-- Mostrar mensaje si no hay servicios disponibles con cupos -->
                            <div class="alert alert-info text-center w-100">
                                <i class="fas fa-info-circle"></i>
                                No hay programas de servicio social con cupos disponibles en este momento.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Barra de paginación -->
                    <?php if ($total_paginas > 1) : ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-5">
                            <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                                <li class="page-item <?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                <?php else : ?>
                    <!-- Mostrar mensaje cuando no se puede seleccionar servicio -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-clock"></i> Selección de Servicio Social No Disponible</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        En este momento no es posible seleccionar programas de servicio social.
                                    </p>
                                    <p class="card-text">
                                        Por favor, revisa las fechas establecidas y regresa durante el período de selección.
                                    </p>
                                    <div class="mt-3">
                                        <a href="IndexAlum.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Regresar al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <?php include '../../Modulos/footer.php'; ?>
    </div>
    <script src="../../js/scripts.js"></script>
    <script src="../../js/datatables-simple-demo.js"></script>
</body>
</html>