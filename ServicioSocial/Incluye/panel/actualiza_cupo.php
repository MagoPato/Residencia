<?php
session_start();
include '../../Modulos/conexion.php';
include '../../Seguridad/control_sesion.php';

// Obtener el ID del servicio desde la URL
$id_servicio = isset($_GET['id_servicio']) ? intval($_GET['id_servicio']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario
    $carreras = isset($_POST['Carrera']) ? $_POST['Carrera'] : [];
    $cupo = isset($_POST['Cupo']) ? intval($_POST['Cupo']) : 0;
    $horario = isset($_POST['horario_servicio']) ? $_POST['horario_servicio'] : '';
    
    // Verificar si se seleccionó "Todas"
    $todas_carreras = in_array('Todas', $carreras);
    
    // Lista de todas las carreras disponibles
    $carreras_disponibles = ['IA', 'IEM', 'IE', 'IGE', 'II', 'IMCT', 'ISC', 'ISM'];
    
    // Si se seleccionó "Todas", usar todas las carreras
    if ($todas_carreras) {
        $carreras = $carreras_disponibles;
    }
    
    // Procesar cada carrera seleccionada
    foreach ($carreras as $carrera) {
        // Verificar si ya existe un cupo para esta carrera y servicio
        $stmt = $enlace->prepare("SELECT id FROM cupos WHERE Id_Servicio = ? AND Carrera = ?");
        $stmt->bind_param("is", $id_servicio, $carrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Actualizar cupo existente
            $update_stmt = $enlace->prepare("UPDATE cupos SET Cupo = ?, Horario = ? WHERE Id_Servicio = ? AND Carrera = ?");
            $update_stmt->bind_param("isis", $cupo, $horario, $id_servicio, $carrera);
            $update_success = $update_stmt->execute();
        } else {
            // Insertar nuevo cupo
            $insert_stmt = $enlace->prepare("INSERT INTO cupos (Id_Servicio, Carrera, Cupo, Horario) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("isis", $id_servicio, $carrera, $cupo, $horario);
            $insert_success = $insert_stmt->execute();
        }
    }
    
    // Respuesta JSON para AJAX
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success', 
        'message' => 'Cupos actualizados correctamente',
        'updated' => isset($update_success) ? $update_success : false,
        'inserted' => isset($insert_success) ? $insert_success : false
    ]);
    exit;
}

// Obtener información del servicio
$stmt = $enlace->prepare("SELECT Id, Programa FROM servicio WHERE Id = ?");
$stmt->bind_param("i", $id_servicio);
$stmt->execute();
$result = $stmt->get_result();
$servicio = $result->fetch_assoc();

if (!$servicio) {
    die("Servicio no encontrado");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Actualizar Cupos de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Actualizar Cupos de Servicio</h1>
                <div class="card mb-4">
                    <div class="card-body"> 
                        <form id="CupoForm" method="POST">
                            <input type="hidden" id="Id_Servicio" name="Id_Servicio" value="<?php echo htmlspecialchars($id_servicio); ?>" />
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="id_programa" class="form-label">ID del Programa</label>
                                    <input type="text" class="form-control" id="id_programa" name="id_programa" value="<?php echo htmlspecialchars($id_servicio); ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="nombre_programa" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" value="<?php echo htmlspecialchars($servicio['Programa']); ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3"> 
                                <div class="col-md-4">
                                    <label class="form-label">Carreras</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="IA" id="carreraIA">
                                        <label class="form-check-label" for="carreraIA">Ingeniería Ambiental</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="IEM" id="carreraIEM">
                                        <label class="form-check-label" for="carreraIEM">Ingeniería Electromecánica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="IE" id="carreraIE">
                                        <label class="form-check-label" for="carreraIE">Ingeniería Electrónica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="IGE" id="carreraIGE">
                                        <label class="form-check-label" for="carreraIGE">Ingeniería en Gestión Empresarial</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="II" id="carreraII">
                                        <label class="form-check-label" for="carreraII">Ingeniería Industrial</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="IMCT" id="carreraIMCT">
                                        <label class="form-check-label" for="carreraIMCT">Ingeniería Mecatrónica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="ISC" id="carreraISC">
                                        <label class="form-check-label" for="carreraISC">Ingeniería en Sistemas Computacionales</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="ISM" id="carreraISM">
                                        <label class="form-check-label" for="carreraISM">Ingeniería en Semiconductores</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Todas" id="carreraAll">
                                        <label class="form-check-label" for="carreraAll">Todas</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="Cupo" class="form-label">Número de Plazas</label>
                                    <input type="number" class="form-control" id="Cupo" name="Cupo" min="1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="horario_servicio" class="form-label">Horario</label>
                                    <select class="form-select" id="horario_servicio" name="horario_servicio" required>
                                        <option value="">Selecciona un horario</option>
                                        <option value="8:00 - 12:00">8:00 - 12:00</option>
                                        <option value="9:00 - 13:00">9:00 - 13:00</option>
                                        <option value="10:00 - 14:00">10:00 - 14:00</option>
                                        <option value="11:00 - 15:00">11:00 - 15:00</option>
                                        <option value="12:00 - 16:00">12:00 - 16:00</option>
                                        <option value="13:00 - 17:00">13:00 - 17:00</option>
                                        <option value="14:00 - 18:00">14:00 - 18:00</option>
                                        <option value="15:00 - 19:00">15:00 - 19:00</option>
                                        <option value="16:00 - 20:00">16:00 - 20:00</option>
                                        <option value="17:00 - 21:00">17:00 - 21:00</option>
                                        <option value="18:00 - 22:00">18:00 - 22:00</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-primary" id="updateCupoBtn">Actualizar Cupos</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php include '../../Modulos/footer.php'; ?>
    </div>
    <script>
        $(document).ready(function() {
            // Manejar el checkbox "Todas"
            $('#carreraAll').change(function() {
                if($(this).is(':checked')) {
                    $('input[name="Carrera[]"]').not(this).prop('checked', false);
                }
            });
            
            // Manejar otros checkboxes
            $('input[name="Carrera[]"]').not('#carreraAll').change(function() {
                if($(this).is(':checked')) {
                    $('#carreraAll').prop('checked', false);
                }
            });
            
            // Manejar el botón de actualización
            $('#updateCupoBtn').on('click', function() {
                const selectedCarreras = $('input[name="Carrera[]"]:checked').length;
                const cupoValue = $('#Cupo').val();
                const horarioValue = $('#horario_servicio').val();

                if (selectedCarreras === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, selecciona al menos una carrera.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                if (!cupoValue) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, ingresa un número de cupos.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                if (!horarioValue) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, selecciona un horario.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                const formData = $('#CupoForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: window.location.href,
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Limpiar el formulario
                                $('#CupoForm')[0].reset();
                                // Opcional: eliminar el formulario del DOM
                                // $('#CupoForm').remove();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Ocurrió un error desconocido',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'Ocurrió un error al intentar comunicarse con el servidor: ' + error,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>