<?php
    session_start();
	
	include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos

	include '../../Seguridad/control_sesion.php'; 
    
    // Inicializar variables
    $Id_Servicio = null;
    $nombre_programa = null;

    // Consultar el último servicio registrado
    $stmt = $enlace->prepare("SELECT Id, Programa FROM servicio ORDER BY Id DESC LIMIT 1");

    if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Id_Servicio = $row['Id'];
        $nombre_programa = $row['Programa'];
    } else {
        echo "<script>alert('No se encontró ningún servicio registrado.');</script>";
    }
    } else {
    echo "<script>alert('Error al consultar el último servicio.');</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Agregar Cupos de Servicio</title>
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
                <h1 class="mt-4">Agregar Cupos de Servicio</h1>
                <div class="card mb-4">
                    <div class="card-body"> 
                    <form id="CupoForm" method="POST" action="/residencia/dev/modulos/guardar_cupo.php">
                            <input type="hidden" id="Id_Servicio" name="Id_Servicio" value="<?php echo htmlspecialchars($Id_Servicio); ?>" />
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="id_programa" class="form-label">ID del Programa</label>
                                    <input type="text" class="form-control" id="id_programa" name="id_programa" value="<?php echo htmlspecialchars($Id_Servicio); ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="nombre_programa" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" value="<?php echo htmlspecialchars($nombre_programa); ?>" readonly>
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
                                <button type="button" class="btn btn-primary" id="addCupoBtn">Registrar Información</button>
                                <button type="submit" class="btn btn-success" id="finalizeBtn">Finalizar Proceso</button>
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
    $('#addCupoBtn').on('click', function() {
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

        const formData = $('#CupoForm').serialize();

        $.ajax({
            type: 'POST',
            url: '../../Modulos/guardar_cupo.php',
            data: formData,
            success: function(response) {
                response = JSON.parse(response); // Asegúrate de convertir la respuesta a JSON
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cupo Agregado',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $('#Cupo').val('');
                        $('input[name="Carrera[]"]').prop('checked', false);
                        $('#horario_servicio').val('');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'Ocurrió un error al intentar comunicarse con el servidor.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    $('#finalizeBtn').on('click', function(e) {
        e.preventDefault();
        window.location.href = "../admin/agre_servicio.php";
    });
});

    </script>
</body>
</html>
