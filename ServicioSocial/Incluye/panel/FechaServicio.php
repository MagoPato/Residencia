<?php
session_start();
include '../../Modulos/conexion.php'; // Asegúrate de que esta ruta es correcta

if (!isset($_SESSION['no_control'])) {
    header("Location: " . "https://" . $_SERVER['HTTP_HOST']);
    exit();
}

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['FechaIni']) && isset($_POST['FechaFin'])) {
    $fechaIni = $_POST['FechaIni'];
    $fechaFin = $_POST['FechaFin'];
    
    // Validar que la fecha de fin sea posterior a la fecha de inicio
    if ($fechaFin <= $fechaIni) {
        $error = "La fecha de terminación debe ser posterior a la fecha de inicio.";
    } else {
        // Verificar si ya existe un registro con tipo 'formulario'
        $checkQuery = "SELECT Id FROM fecha_registro WHERE tipo = 'servicio'";
        $checkResult = mysqli_query($enlace, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            // Actualizar registro existente
            $updateQuery = "UPDATE fecha_registro SET Fecha_In = ?, Fecha_Fin = ? WHERE tipo = 'servicio'";
            $stmt = mysqli_prepare($enlace, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ss", $fechaIni, $fechaFin);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Fechas actualizadas correctamente.";
            } else {
                $error = "Error al actualizar las fechas: " . mysqli_error($enlace);
            }
            mysqli_stmt_close($stmt);
        } else {
            // Insertar nuevo registro
            $insertQuery = "INSERT INTO fecha_registro (Fecha_In, Fecha_Fin, tipo) VALUES (?, ?, 'servicio')";
            $stmt = mysqli_prepare($enlace, $insertQuery);
            mysqli_stmt_bind_param($stmt, "ss", $fechaIni, $fechaFin);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Fechas registradas correctamente.";
            } else {
                $error = "Error al registrar las fechas: " . mysqli_error($enlace);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Inicializar variables
$fechaIni = '';
$fechaFin = '';
$modo = "Registrar Fechas";

// Consultar fechas existentes con tipo 'servicio'
$query = "SELECT * FROM fecha_registro WHERE tipo = 'servicio' LIMIT 1";
$resultado = mysqli_query($enlace, $query);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_assoc($resultado);
    $fechaIni = $row['Fecha_In'];
    $fechaFin = $row['Fecha_Fin'];
    $modo = "Actualizar Fechas de Seleccion de Servicio";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Servicio Social - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .alert {
            border-radius: 8px;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 30px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        .current-dates {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">
                    <i class="fas fa-calendar-alt"></i> <?= $modo ?>
                </h1>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit"></i> Configuración de Período de Seleccion de Servicio
                    </div>
                    <div class="card-body">
                        <form method="post" id="fechasForm">
                            <div class="form-section">
                                <h4><i class="fas fa-play-circle text-success"></i> Fecha de Inicio</h4>
                                <p class="text-muted mb-3">Establece cuándo comenzará el período de Seleccion de Servicio</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de Inicio</label>
                                        <input type="date" 
                                               id="FechaIni" 
                                               name="FechaIni" 
                                               class="form-control" 
                                               required 
                                               value="<?= htmlspecialchars($fechaIni) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h4><i class="fas fa-stop-circle text-danger"></i> Fecha de Terminación</h4>
                                <p class="text-muted mb-3">Establece cuándo finalizará el período de Seleccion de Servicio</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de Terminación</label>
                                        <input type="date" 
                                               id="FechaFin" 
                                               name="FechaFin" 
                                               class="form-control" 
                                               required 
                                               value="<?= htmlspecialchars($fechaFin) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> <?= $modo ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../../Modulos/footer.php'; ?>
    </div>

    <script src="../../js/scripts.js"></script>
    <script src="../../js/datatables-simple-demo.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaIni = document.getElementById('FechaIni');
            const fechaFin = document.getElementById('FechaFin');
            
            // Validación en tiempo real
            function validarFechas() {
                if (fechaIni.value && fechaFin.value) {
                    if (fechaFin.value <= fechaIni.value) {
                        fechaFin.setCustomValidity('La fecha de terminación debe ser posterior a la fecha de inicio');
                    } else {
                        fechaFin.setCustomValidity('');
                    }
                }
            }
            
            fechaIni.addEventListener('change', validarFechas);
            fechaFin.addEventListener('change', validarFechas);
            
            // Auto-ocultar mensajes de éxito después de 5 segundos
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>