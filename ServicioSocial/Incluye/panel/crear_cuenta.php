<?php
// Conexión a la base de datos
include '../../Modulos/conexion.php';
include '../../Seguridad/control_sesion.php';

// Obtener el ID del alumno desde la URL (No_Control)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Consulta para obtener los datos del alumno
    $sql = "
        SELECT 
            a.Nombre, a.Apellido_P, a.Apellido_M, a.No_Control, a.Carrera, a.Correo, a.Telefono, a.Semestre,
            c.Estatus, c.Tipo_Usuario
        FROM 
            alumno a
        LEFT JOIN 
            cuenta c
        ON 
            a.No_Control = c.Usuario
        WHERE 
            a.No_Control = ?";
    $stmt = $enlace->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del alumno
        $alumno = $result->fetch_assoc();
    } else {
        $alumno = null;
    }
    $stmt->close();
} else {
    $alumno = null;
}

$enlace->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Servicio Social - Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .form-control, .form-select {
            width: 100% !important;
            margin-bottom: 1rem;
        }
        
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .student-info .card-body {
            padding: 2rem;
        }
        
        .student-info p {
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .btn {
            border-radius: 0.375rem;
            padding: 0.75rem 1.5rem;
        }
        
        .alert {
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
        
        h5 {
            margin-bottom: 2rem;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-12">
                        <h5 class="text-center mb-4 mt-4">Detalles del Alumno Aceptado</h5>
                        
                        <?php if ($alumno): ?>
                            <!-- Información del Alumno -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card shadow student-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-user me-2"></i>
                                                Información del Estudiante
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong><i class="fas fa-id-card me-2"></i>Nombre Completo:</strong> 
                                                       <?php echo htmlspecialchars($alumno['Nombre'] . ' ' . $alumno['Apellido_P'] . ' ' . $alumno['Apellido_M']); ?></p>
                                                    <p><strong><i class="fas fa-hashtag me-2"></i>No. Control:</strong> 
                                                       <?php echo htmlspecialchars($alumno['No_Control']); ?></p>
                                                    <p><strong><i class="fas fa-graduation-cap me-2"></i>Carrera:</strong> 
                                                       <?php echo htmlspecialchars($alumno['Carrera']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong><i class="fas fa-envelope me-2"></i>Correo:</strong> 
                                                       <?php echo htmlspecialchars($alumno['Correo']); ?></p>
                                                    <p><strong><i class="fas fa-calendar me-2"></i>Semestre:</strong> 
                                                       <?php echo htmlspecialchars($alumno['Semestre']); ?></p>
                                                    <p><strong><i class="fas fa-phone me-2"></i>Teléfono:</strong> 
                                                       <?php echo htmlspecialchars($alumno['Telefono']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de Creación de Cuenta -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card shadow">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0 text-center">
                                                <i class="fas fa-user-plus me-2"></i>
                                                Crear Cuenta de Alumno
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <form id="programaForm">
                                                <!-- Campo No_Control (oculto) -->
                                                <input type="hidden" name="inputNo_Control" value="<?php echo htmlspecialchars($alumno['No_Control']); ?>">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Campo Usuario -->
                                                        <div class="form-floating mb-3">
                                                            <input class="form-control" id="inputUser" name="inputUser" type="text" 
                                                                   placeholder="Usuario" required readonly
                                                                   value="<?php echo htmlspecialchars($alumno['No_Control']); ?>">
                                                            <label for="inputUser">
                                                                <i class="fas fa-user me-2"></i>Usuario
                                                            </label>
                                                        </div>
                                                        
                                                        <!-- Campo Contraseña -->
                                                        <div class="form-floating mb-3">
                                                            <input class="form-control" id="inputPassword" name="inputPassword" 
                                                                   type="password" placeholder="Contraseña" required
                                                                   value="<?php echo htmlspecialchars($alumno['No_Control']); ?>">
                                                            <label for="inputPassword">
                                                                <i class="fas fa-lock me-2"></i>Contraseña
                                                            </label>
                                                            <div class="form-text">
                                                                <small><i class="fas fa-info-circle me-1"></i>
                                                                Por defecto será el número de control</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <!-- Campo Tipo de Usuario -->
                                                        <div class="form-floating mb-3">
                                                            <select class="form-select" id="Tipo_Usuario" name="Tipo_Usuario" required>
                                                                <option value="" disabled>Seleccione Tipo de Usuario</option>
                                                                <option value="U" selected>Alumno</option>
                                                            </select>
                                                            <label for="Tipo_Usuario">
                                                                <i class="fas fa-users me-2"></i>Tipo de Usuario
                                                            </label>
                                                        </div>

                                                        <!-- Campo Estatus -->
                                                        <div class="form-floating mb-3">
                                                            <select class="form-select" id="inputEstatus" name="inputEstatus" required>
                                                                <option value="" disabled>Estado de Usuario</option>
                                                                <option value="A" selected>Aceptado</option>
                                                            </select>
                                                            <label for="inputEstatus">
                                                                <i class="fas fa-check-circle me-2"></i>Estado de Usuario
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Botón de envío -->
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                                    <button type="button" id="submitBtn" class="btn btn-primary btn-lg me-md-2">
                                                        <i class="fas fa-save me-2"></i>Crear Cuenta
                                                    </button>
                                                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                                                        <i class="fas fa-arrow-left me-2"></i>Volver
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <?php else: ?>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-danger text-center" role="alert">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                        <h6>No se encontraron detalles</h6>
                                        <p class="mb-0">No se encontraron detalles para el alumno solicitado.</p>
                                    </div>
                                    <div class="text-center">
                                        <a href="javascript:history.back()" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Volver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../../Modulos/footer.php'; ?>
    </div>

    <script>
        $(document).ready(function () {
            $("#submitBtn").click(function (e) {
                e.preventDefault();
                
                // Validar que todos los campos requeridos estén llenos
                var form = $("#programaForm")[0];
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                
                // Mostrar loading
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...');
                
                var formData = $("#programaForm").serialize();

                $.ajax({
                    type: "POST",
                    url: "../../Modulos/Registro_usuario.php",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro Exitoso',
                                text: response.message,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'acept_alumno.php';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'Hubo un problema al enviar los datos. Por favor, inténtelo de nuevo.',
                        });
                    },
                    complete: function() {
                        // Restaurar botón
                        $("#submitBtn").prop('disabled', false).html('<i class="fas fa-save me-2"></i>Crear Cuenta');
                    }
                });
            });
        });
    </script>
</body>
</html>