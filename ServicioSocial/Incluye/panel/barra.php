<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    
    <a class="navbar-brand ps-3" href="IndexAdmin.php">Servicio Social</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="../../Modulos/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesion</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading"><i class="fas fa-building me-2"></i>Jefatura de Servicio Social</div>
                <a class="nav-link" href="IndexAdmin.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        Perfil </a>
                    <div class="sb-sidenav-menu-heading"><i class="fas fa-cogs me-2"></i> Administración</div>

                <a class="nav-link collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-folder-open"></i></div>
                        Documentos
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

					
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="FechaServicio.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-week"></i></div>
                         Fechas de Seleccion
                    </a>            
                    <a class="nav-link" href="FechaReg.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-week"></i></div>
                         Fechas de Registro
                    </a>
                        </nav>
                    </div>
                <a class="nav-link collapsed"  data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                        Administrar
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                            <a class="nav-link collapsed"  data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                <i class="fas fa-plus-circle me-2"></i>Registrar
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="agre_alumnos.php"><i class="fas fa-user-plus me-2"></i>Estudiantes</a>
                                    <a class="nav-link" href="agre_servicio.php"><i class="fas fa-hands-helping me-2"></i>Programas de Servicio</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed"  data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                <i class="fas fa-edit me-2"></i>Actualizar
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseError" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="act_alumnos.php"><i class="fas fa-user-edit me-2"></i>Estudiantes</a>
                                    <a class="nav-link" href="act_servicio.php"><i class="fas fa-edit me-2"></i>Programas de Servicio</a>
                                </nav>
                            </div>  
                        </nav>
                    </div>
               <!-- Alumnos -->
                            <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
    <div class="sb-nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
    Alumnos
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse" id="collapseStudents" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="acept_alumno.php">
            <div class="sb-nav-link-icon"><i class="fas fa-check-circle"></i></div>
            Aceptación
        </a>
    </nav>
</div>

<!-- Consultas -->
<a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseConsultas" aria-expanded="false" aria-controls="collapseConsultas">
    <div class="sb-nav-link-icon"><i class="fas fa-search"></i></div>
    Consultar
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse" id="collapseConsultas" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="consultas.php">
            <div class="sb-nav-link-icon"><i class="fas fa-eye"></i></div>
            Ver Consultas
        </a>
    </nav>
</div>
                        <!-- Reportes -->
<a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseRepotes" aria-expanded="false" aria-controls="collapseConsultas">
    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
    Cartas
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse" id="collapseRepotes" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="CartaAsignacion.php">
            <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
           Asignación
        </a>
    </nav>
</div>
                        
                        <div class="sb-sidenav-menu-heading"><i class="fas fa-sliders-h me-2"></i>Opciones</div>
                    <a class="nav-link" href="../../Modulos/logout.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                        Cerrar sesión
                    </a>

      
                </div> 
            </div>
        </nav>
    </div>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle JS (incluye Popper.js y Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>