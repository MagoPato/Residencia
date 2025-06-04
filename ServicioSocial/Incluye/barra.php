<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand -->
    <a class="navbar-brand ps-3" href="<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>">Servicio Social</a>

    <!-- Espaciador para empujar elementos a la derecha -->
    <div class="ms-auto d-flex">
        <?php
        $paginaActual = basename($_SERVER['PHP_SELF']);
        if ($paginaActual == "index.php") {
            echo '<a class="nav-link text-white me-3" href="../Incluye/info.php">Información</a>';
        } elseif ($paginaActual == "info.php") {
            $loginUrl = 'https://' . $_SERVER['HTTP_HOST'];
            echo '<a class="nav-link text-white me-3" href="' . $loginUrl . '">Login</a>';
        }else{
                echo '<a class="nav-link text-white me-3" href="../Incluye/info.php">Información</a>';
                 $loginUrl = 'https://' . $_SERVER['HTTP_HOST'];
            echo '<a class="nav-link text-white me-3" href="' . $loginUrl . '">Login</a>';
		}
        ?>
        <a class="nav-link text-white me-3" href="../forms/Formulario.php">Formulario</a>
    </div>
</nav>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
