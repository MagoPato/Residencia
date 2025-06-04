<?php
include '../../Seguridad/control_sesion.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title> Servicio Social - Administrador </title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
<?php
include '../../Modulos/conexion.php';

$query = "
    SELECT s.Id, d.Nombre AS dependencia, d.Encargado_N, s.Programa, s.Departamento 
    FROM servicio s 
    JOIN dependencia d ON s.Id_Dependencia = d.Id";
$result = $enlace->query($query);
$servicios_sociales = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios_sociales[] = array(
            'id' => $row['Id'],
            'programa' => $row['Programa'],
            'dependencia' => $row['dependencia'],
            'departamento' => $row['Departamento']
        );
    }
}

$servicios_por_pagina = 9;
$pagina_actual = isset($_GET['page']) ? $_GET['page'] : 1;
$indice_inicio = ($pagina_actual - 1) * $servicios_por_pagina;
$servicios_pagina = array_slice($servicios_sociales, $indice_inicio, $servicios_por_pagina);
?>
<?php include 'barra.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container">
            <h1 class="text-center mb-5">Programas Activos</h1>
            <div class="row">
                <?php foreach ($servicios_pagina as $servicio) { ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($servicio['programa']); ?></h5>
                                <p class="card-text"><strong>Dependencia:</strong> <?php echo htmlspecialchars($servicio['dependencia']); ?></p>
                                <p class="card-text"><strong>Departamento:</strong> <?php echo htmlspecialchars($servicio['departamento']); ?></p>
                                <button onclick="window.location.href='FichaServicio.php?id_servicio=<?php echo urlencode($servicio['id']); ?>';" class="btn btn-primary">
                                    Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-5">
                    <?php for ($i = 1; $i <= ceil(count($servicios_sociales) / $servicios_por_pagina); $i++) : ?>
                        <li class="page-item <?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </main>
    <?php include '../../Modulos/footer.php'; ?>
</div>

<script src="../../js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>
</body>

</html>
