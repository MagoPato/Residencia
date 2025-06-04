<?php include_once '../../Modulos/conexion.php'; include_once '../../Seguridad/control_sesion.php';
?>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="IndexAlum.php">Servicio Social</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="datos.php">Datos Generales</a></li>
                <li><a class="dropdown-item" href="../../Modulos/logout.php">Cerrar sesion</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <?php
                    // Obtener el ID del alumno desde la sesión
                    $Id_Alumno = $_SESSION['no_control'];
                    
                    // Verificar si el alumno tiene cuenta creada
                    $query_cuenta = "SELECT COUNT(*) as total FROM cuenta WHERE Id_Alumno = ?";
                    $stmt_cuenta = $enlace->prepare($query_cuenta);
                    $stmt_cuenta->bind_param("s", $Id_Alumno);
                    $stmt_cuenta->execute();
                    $result_cuenta = $stmt_cuenta->get_result();
                    $row_cuenta = $result_cuenta->fetch_assoc();
                    
                    $tieneCuenta = ($row_cuenta['total'] > 0);
                    
                    if ($tieneCuenta) {
                        // Si tiene cuenta, mostrar el menú completo
                    ?>
                        <div class="sb-sidenav-menu-heading">Mis Datos</div>
                        <a class="nav-link" href="datos.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-address-card"></i></div>
                            Datos Generales
                        </a>
                        <div class="sb-sidenav-menu-heading">Servicio</div>
                        <?php
                        // Verificar si el alumno está inscrito
                        $query = "SELECT COUNT(*) as total FROM inscripciones WHERE Id_Alumno = ?";
                        $stmt = $enlace->prepare($query);
                        $stmt->bind_param("s", $Id_Alumno);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        
                        $servicioSeleccionado = ($row['total'] > 0);
                        
                        if ($servicioSeleccionado) {
                        ?>
                             <a class="nav-link" href="DatosServicio.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                                Datos Servicio
                            </a>
                             <a class="nav-link" href="GeneraSolicitud.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-plus-circle"></i></div>
                                Generar Solicitud
                            </a>
                            <a class="nav-link" href="GenerarReporte.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                Generar Reporte
                            </a>
                        <?php
                        } else {
                        ?>
                            <a class="nav-link" href="SelecionServicio.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-check"></i></div>
                                Selección de Programa
                            </a>
                        <?php
                        }
                        ?>
                        
                        <div class="sb-sidenav-menu-heading">Opciones</div>
                        <a class="nav-link" href="../../Modulos/logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Cerrar sesión
                        </a>
                    <?php
                    } else {
                        // Si NO tiene cuenta, solo mostrar opciones básicas
                    ?>
                        <div class="sb-sidenav-menu-heading">Opciones</div>
                        <a class="nav-link" href="informacion.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-info-circle"></i></div>
                            Información
                        </a>
                        <a class="nav-link" href="../../Modulos/logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Cerrar sesión
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Bundle JS (incluye Popper.js y Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>