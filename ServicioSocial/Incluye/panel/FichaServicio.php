<?php
session_start();
include '../../Modulos/conexion.php'; // aquí asumimos que se crea $conn
include '../../Seguridad/control_sesion.php';

$id_servicio = isset($_GET['id_servicio']) ? intval($_GET['id_servicio']) : 0;
$mensaje = "";

// ELIMINAR SI SE RECIBE LA SOLICITUD
if (isset($_GET['eliminar']) && $_GET['eliminar'] == 'true' && $id_servicio > 0) {
    try {
        // Iniciar transacción para asegurar integridad
        $enlace->autocommit(false);
        
        // Primero eliminamos los cupos asociados
        $stmt_cupos = $enlace->prepare("DELETE FROM cupos WHERE Id_Servicio = ?");
        if (!$stmt_cupos) {
            throw new Exception("Error al preparar eliminación de cupos: " . $enlace->error);
        }
        $stmt_cupos->bind_param("i", $id_servicio);
        if (!$stmt_cupos->execute()) {
            throw new Exception("Error al eliminar cupos: " . $stmt_cupos->error);
        }
        $stmt_cupos->close();
        
        // Luego eliminamos el servicio
        $stmt = $enlace->prepare("DELETE FROM servicio WHERE Id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar eliminación de servicio: " . $enlace->error);
        }
        $stmt->bind_param("i", $id_servicio);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar servicio: " . $stmt->error);
        }
        
        if ($stmt->affected_rows > 0) {
            // Confirmar transacción
            $enlace->commit();
            $stmt->close();
            
            // Redirigir con JavaScript
            echo "<!DOCTYPE html>
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Servicio eliminado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'act_servicio.php';
                    });
                </script>
            </body>
            </html>";
            exit();
        } else {
            throw new Exception("No se encontró el servicio a eliminar");
        }
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $enlace->rollback();
        $mensaje = "Error al eliminar: " . $e->getMessage();
        
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '" . addslashes($mensaje) . "'
                });
            </script>
        </body>
        </html>";
    } finally {
        // Restaurar autocommit
        $enlace->autocommit(true);
    }
}

// ACTUALIZAR SI SE ENVÍA EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_servicio'])) {
    try {
        // Obtener Id_Dependencia basado en el nombre
        $stmt_dep = $enlace->prepare("SELECT Id FROM dependencia WHERE Nombre = ? LIMIT 1");
        if (!$stmt_dep) {
            throw new Exception("Error al preparar consulta de dependencia: " . $enlace->error);
        }
        
        $stmt_dep->bind_param("s", $_POST['nombre_dependencia']);
        $stmt_dep->execute();
        $result_dep = $stmt_dep->get_result();
        $id_dependencia = 0;
        
        if ($result_dep->num_rows > 0) {
            $row_dep = $result_dep->fetch_assoc();
            $id_dependencia = $row_dep['Id'];
        }
        $stmt_dep->close();
        
        if ($id_dependencia == 0) {
            throw new Exception("No se encontró la dependencia especificada");
        }

        $stmt = $enlace->prepare("UPDATE servicio SET 
            Id_Dependencia=?, 
            Programa=?, 
            Departamento=?, 
            Actividades=?, 
            Tipo=?
            WHERE Id=?");

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de actualización: " . $enlace->error);
        }

        $stmt->bind_param("issssi",
            $id_dependencia,
            $_POST['nombre_programa'],
            $_POST['departamento'],
            $_POST['actividades'],
            $_POST['tipo_servicio'],
            $_POST['id_servicio']
        );

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Servicio actualizado correctamente.";
                // Actualizar los datos mostrados después de la actualización
                $id_servicio = $_POST['id_servicio'];
            } else {
                $mensaje = "No se realizaron cambios en el servicio.";
            }
        } else {
            throw new Exception("Error al actualizar: " . $stmt->error);
        }
        $stmt->close();
        
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
    }
}

// CONSULTAR DATOS
$datos = [
    'Id_Dependencia' => 0,
    'Programa' => '',
    'Departamento' => '',
    'Actividades' => '',
    'Tipo' => ''
];

// Datos de dependencia/responsable
$datos_dependencia = [
    'Nombre' => '',
    'Encargado_N' => '',
    'Encargado_A' => '',
    'Puesto_En' => ''
];

$datos_responsable = [
    'nombre' => '',
    'puesto' => ''
];

if ($id_servicio > 0) {
    try {
        // Consultar servicio
        $stmt = $enlace->prepare("SELECT * FROM servicio WHERE Id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de servicio: " . $enlace->error);
        }
        
        $stmt->bind_param("i", $id_servicio);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta de servicio: " . $stmt->error);
        }
        
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $datos = $resultado->fetch_assoc();
            
            // Consultar dependencia relacionada
            if ($datos['Id_Dependencia'] > 0) {
                $stmt_dep = $enlace->prepare("SELECT * FROM dependencia WHERE Id = ?");
                if ($stmt_dep) {
                    $stmt_dep->bind_param("i", $datos['Id_Dependencia']);
                    if ($stmt_dep->execute()) {
                        $result_dep = $stmt_dep->get_result();
                        if ($result_dep->num_rows > 0) {
                            $datos_dependencia = $result_dep->fetch_assoc();
                        }
                    }
                    $stmt_dep->close();
                }
            }
            
            // Consultar responsable si existe
            if (!empty($datos['Id_Responsable'])) {
                $stmt_resp = $enlace->prepare("SELECT * FROM responsable WHERE id = ?");
                if ($stmt_resp) {
                    $stmt_resp->bind_param("i", $datos['Id_Responsable']);
                    if ($stmt_resp->execute()) {
                        $result_resp = $stmt_resp->get_result();
                        if ($result_resp->num_rows > 0) {
                            $datos_responsable = $result_resp->fetch_assoc();
                        }
                    }
                    $stmt_resp->close();
                }
            }
        } else {
            $mensaje = "No se encontró el servicio con ID: $id_servicio";
        }
        $stmt->close();
        
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Actualizar Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <!-- Iconos -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminacion() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto! Se eliminarán también todos los cupos asociados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading mientras se procesa
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    window.location.href = 'FichaServicio.php?id_servicio=<?= $id_servicio ?>&eliminar=true';
                }
            });
        }
    </script>
</head>
<body class="sb-nav-fixed">
<?php include 'barra.php'; ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Actualizar Programa de Servicio</h1>

            <?php if (!empty($mensaje) && !isset($_GET['eliminar'])): ?>
                <script>
                    Swal.fire({
                        icon: '<?php echo strpos($mensaje, "correctamente") !== false ? "success" : "error"; ?>',
                        title: '<?php echo strpos($mensaje, "correctamente") !== false ? "Éxito" : "Error"; ?>',
                        text: '<?php echo addslashes($mensaje); ?>'
                    });
                </script>
            <?php endif; ?>

            <?php if ($id_servicio > 0 && empty($mensaje)): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_servicio" value="<?= $id_servicio ?>">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre de la Dependencia</label>
                                <input type="text" class="form-control" name="nombre_dependencia" value="<?= htmlspecialchars($datos_dependencia['Nombre'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre del responsable</label>
                                <input type="text" class="form-control" name="Nombre_responsable" value="<?= htmlspecialchars($datos_responsable['nombre'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Puesto del responsable</label>
                                <input type="text" class="form-control" name="puesto_responsable" value="<?= htmlspecialchars($datos_responsable['puesto'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Programa</label>
                                <input type="text" class="form-control" name="nombre_programa" value="<?= htmlspecialchars($datos['Programa'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Actividades</label>
                                <textarea class="form-control" name="actividades" required><?= htmlspecialchars($datos['Actividades'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre de Encargado</label>
                                <input type="text" class="form-control" name="EncargadoNom" value="<?= htmlspecialchars($datos_dependencia['Encargado_N'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido de Encargado</label>
                                <input type="text" class="form-control" name="EncargadoApe" value="<?= htmlspecialchars($datos_dependencia['Encargado_A'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Puesto de Encargado</label>
                                <input type="text" class="form-control" name="PuestoEn" value="<?= htmlspecialchars($datos_dependencia['Puesto_En'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Departamento</label>
                                <input type="text" class="form-control" name="departamento" value="<?= htmlspecialchars($datos['Departamento'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo de servicio</label>
                                <select class="form-select" name="tipo_servicio" required>
                                    <option value="">Selecciona el tipo</option>
                                    <?php
                                    $tipos = [
                                        "EpA" => "Educación para adultos",
                                        "ACv" => "Actividades Cívicas",
                                        "DSus" => "Desarrollo sustentable",
                                        "DesCom" => "Desarrollo de Comunidad",
                                        "ACl" => "Actividades Culturales",
                                        "ApSld" => "Apoyo a la Salud",
                                        "ActDep" => "Actividades Deportivas",
                                        "MedAmb" => "Medio Ambiente",
                                        "Otrs" => "Otro"
                                    ];
                                    foreach ($tipos as $clave => $valor) {
                                        $selected = (isset($datos['Tipo']) && $datos['Tipo'] == $clave) ? 'selected' : '';
                                        echo "<option value='$clave' $selected>$valor</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Actualizar Servicio
                                </button>
                                <a href="actualiza_cupo.php?id_servicio=<?= $id_servicio ?>" class="btn btn-info">
                                    <i class="fas fa-users me-1"></i> Actualizar Cupo
                                </a>
                                <a href="act_servicio.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="confirmarEliminacion()">
                                <i class="fas fa-trash me-1"></i> Eliminar Servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= $mensaje ?: "No se proporcionó un ID de servicio válido." ?>
                <br><br>
                <a href="act_servicio.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a la lista de servicios
                </a>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../../Modulos/footer.php'; ?>
</div>
</body>
</html>