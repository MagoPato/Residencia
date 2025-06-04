<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro - SB Admin</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #DFE9F0;
        }
    .card {
        margin-top: 5%;
        margin-bottom: 5%;
        background-color: #DFE9F0;
        color: black;
    }

    .card-header {
        background-color: white;
        color: black;
    }

    .btn {
        background-color: #2780D8;
        color: white;
        transition: 0.3s;
        border-radius: 4px;

        &:hover {
            background-color: #005EA1;
            color: #f2f2f2;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el campo de código postal
        var inputPostalCode = document.getElementById('inputPostalCode');
        var inputStudentID = document.getElementById('inputStudentID');
        var inputCorreo = document.getElementById('inputCorreo');

        // Función para generar correo automático
        function generateEmail() {
            var numeroControl = inputStudentID.value.trim();
            if (numeroControl !== '') {
                var correoGenerado = 'l' + numeroControl + '@nuevoleon.tecnm.mx';
                inputCorreo.value = correoGenerado;
            }
        }

        // Agregar listener para generar correo cuando cambie el número de control
        inputStudentID.addEventListener('input', generateEmail);
        inputStudentID.addEventListener('blur', generateEmail);

        // Agregar un listener para el evento blur del código postal
        inputPostalCode.addEventListener('blur', function() {
            // Obtener el valor del código postal
            var codigoPostal = inputPostalCode.value.trim();

            // Verificar si el código postal no está vacío
            if (codigoPostal !== '') {
                // URL base de la API de Zippopotamus
                var url = 'https://api.zippopotam.us/mx/' + codigoPostal;

                // Realizar la solicitud HTTP GET a la API
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Actualizar los campos del formulario con los datos obtenidos de la API
                        document.getElementById('inputCity').value = data.places[0]['place name'];
                        document.getElementById('inputState').value = data.places[0]['state'];
                    })
                    .catch(error => console.error('Error al obtener datos del código postal:', error));
            }
        });

        // Función para mostrar confirmación antes de enviar
        function confirmarRegistro(event) {
            event.preventDefault(); // Prevenir el envío inmediato del formulario
            
            var confirmacion = confirm("¿Está seguro de que todos sus datos son correctos? Una vez registrado no podrá modificar la información.");
            
            if (confirmacion) {
                // Si confirma, enviar el formulario
                event.target.submit();
            }
            // Si cancela, no hace nada (el formulario no se envía)
        }

        // Agregar listener al formulario para la confirmación
        document.getElementById('registroForm').addEventListener('submit', confirmarRegistro);
    });
</script>

</head>
<body>
  <?php
    // contenido.php
    include 'barra.php';
    ?>        
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Formulario de Registro</h3>
                            </div>
                        <div class="card-body">
                        <?php
                            include('../Modulos/conexion.php');
                            
                            // Obtener las fechas desde la base de datos con tipo 'formulario'
                            $fecha_inicio = '';
                            $fecha_fin = '';
                            
                            try {
                                $query = "SELECT Fecha_In, Fecha_Fin FROM fecha_registro WHERE tipo = 'formulario' LIMIT 1";
                                $resultado = mysqli_query($enlace, $query);
                                
                                if ($resultado && mysqli_num_rows($resultado) > 0) {
                                    $row = mysqli_fetch_assoc($resultado);
                                    $fecha_inicio = $row['Fecha_In'];
                                    $fecha_fin = $row['Fecha_Fin'];
                                } else {
                                    // Si no hay datos en la base de datos, usar fechas por defecto
                                    $fecha_inicio = '2024-03-15';
                                    $fecha_fin = '2026-04-30';
                                    echo "<div class='alert alert-warning'>No se pudieron obtener las fechas de registro desde la base de datos. Usando fechas por defecto.</div>";
                                }
                            } catch (Exception $e) {
                                // En caso de error, usar fechas por defecto
                                $fecha_inicio = '2024-03-15';
                                $fecha_fin = '2026-04-30';
                                echo "<div class='alert alert-danger'>Error al obtener las fechas de registro: " . $e->getMessage() . "</div>";
                            }

                            // Obtener la fecha y hora actual
                            $fecha_actual = date('Y-m-d');
                            $hora_actual = date('H:i');
                            $hora_actual_minutos = strtotime($hora_actual) / 60; // Convertir la hora actual a minutos desde la medianoche

                            // Obtener la hora de inicio y fin permitida
                            $hora_inicio = '00:00'; // Hora de inicio permitida (12:00 AM)
                            $hora_inicio_minutos = strtotime($hora_inicio) / 60; // Convertir la hora de inicio a minutos desde la medianoche

                            $hora_fin = '24:00'; // Hora de fin permitida (12:00 AM)
                            $hora_fin_minutos = strtotime($hora_fin) / 60; // Convertir la hora de fin a minutos desde la medianoche

                            // Verificar si la fecha actual está dentro del rango permitido
                            if (($fecha_actual >= $fecha_inicio && $fecha_actual <= $fecha_fin) &&
                                ($hora_actual_minutos >= $hora_inicio_minutos && $hora_actual_minutos <= $hora_fin_minutos)
                            ) {
                        ?>
                                <div class="alert alert-info mb-4">
                                    <strong>Período de registro activo:</strong>
                                    <span class="ms-2">Desde: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?></span>
                                    <span class="ms-3">Hasta: <?php echo date('d/m/Y', strtotime($fecha_fin)); ?></span>
                                </div>
                                
                                <form id="registroForm" action="../Modulos/registro_alumno.php" method="post">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputApellidoPaterno" name="inputApellidoPaterno" type="text" placeholder="Ingrese su apellido paterno" required />
                                                <label for="inputApellidoPaterno">Apellido Paterno</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputApellidoMaterno" name="inputApellidoMaterno" type="text" placeholder="Ingrese su apellido materno" required />
                                                <label for="inputApellidoMaterno">Apellido Materno</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputFirstName" name="inputFirstName" type="text" placeholder="Ingrese sus nombres completos" required />
                                                <label for="inputFirstName">Nombres Completos</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputGener" name="inputGener" aria-label="Género" required>
                                                    <option selected disabled>Seleccione </option>
                                                    <option value="Femenino">Femenino</option>
                                                    <option value="Masculino">Masculino</option>
                                                </select>
                                                <label for="inputGener">Género</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputTelefono" name="inputTelefono" type="text" placeholder="Telefono" required />
                                                <label for="inputTelefono">Telefono</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputStudentID" name="inputStudentID" type="text" placeholder="Ingrese su número de control" required />
                                                <label for="inputStudentID">Número de Control</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputSemester" name="inputSemester" aria-label="Semester" required>
                                                    <?php
                                                    echo "<option selected disabled>Seleccione semestre</option>";
                                                    $num_semestres = (strpos(strtolower($carrera), 'virtual') !== false) ? : 15;
                                                    // Generar las opciones del select según el número de semestres
                                                    for ($i = 1; $i <= $num_semestres; $i++) {
                                                        echo "<option value='$i'>$i Semestre</option>";
                                                    } ?>
                                                </select>
                                                <label for="inputSemester">Semestre</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputMajor" name="inputMajor" aria-label="Major" required>
                                                    <option selected disabled>Seleccione carrera</option>
                                                    <option value="Ingeniería Ambiental">Ingeniería Ambiental</option>
                                                    <option value="Ingeniería Electromecánica">Ingeniería Electromecánica</option>
                                                    <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                                                    <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
                                                    <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                                                    <option value="Ingeniería Mecatrónica">Ingeniería Mecatrónica</option>
                                                    <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>                                                       
                                                    <option value="Ingeniería en Semiconductores">Ingeniería en Semiconductores</option>
                                                </select>
                                                <label for="inputMajor">Carrera</label>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputModalidad" name="inputModalidad" aria-label="Modalidad" required>
                                                    <option selected disabled>Seleccione Modalidad</option>
                                                    <option value="Presencial">Presencial</option>
                                                    <option value="Virtual">virtual</option>
                                                </select>
                                                <label for="inputTurn">Modalidad</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputCreditos" name="inputCreditos" type="number" placeholder="Créditos acumulados" min="0" max="500" required />
                                                <label for="inputCreditos">Créditos acumulados</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputEstatus" name="inputEstatus" aria-label="Estatus" required>
                                                    <option selected disabled>Estatus</option>
                                                    <option value="Candidato">Candidato</option>
                                                    <option value="Baja">Baja</option>
                                                </select>
                                                <label for="inputEstatus">Estatus</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputCorreo" name="inputCorreo" type="email" placeholder="Correo generado automáticamente" readonly style="background-color: #f8f9fa;" />
                                                <label for="inputCorreo">Correo (Generado automáticamente)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="inputTurn" name="inputTurn" aria-label="Turno" required>
                                                    <option selected disabled>Seleccione turno</option>
                                                    <option value="Mañana">(Matutino) Mañana</option>
                                                    <option value="Tarde">(Vespertino) Tarde</option>
                                                </select>
                                                <label for="inputTurn">Turno</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputStreet" name="inputStreet" type="text" placeholder="C. Calle" required />
                                                <label for="inputStreet">Calle</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputExternalNumber" name="inputExternalNumber" type="text" placeholder="1012" required />
                                                <label for="inputExternalNumber">Número Externo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputInternalNumber" name="inputInternalNumber" type="text" placeholder="101" />
                                                <label for="inputInternalNumber">Número Interno</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-3">

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputColonia" name="inputColonia" type="text" placeholder="Guerra" required />
                                                <label for="inputColonia">Colonia</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPostalCode" name="inputPostalCode" type="text" placeholder="67155" required />
                                                <label for="inputPostalCode">Código Postal</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-3">

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputCity" name="inputCity" type="text" placeholder="Guadalupe" required />
                                                <label for="inputCity">Municipio</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputState" name="inputState" type="text" required />
                                                <label for="inputState">Estado</label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-4 mb-0">
                                        <div class="d-grid"><button type="submit" class="btn btn-primary">Registrar</button></div>
                                    </div>
                                </form>
                            <?php
                            } else {
                                // Mostrar mensaje más detallado cuando el registro no esté disponible
                                echo "<div class='alert alert-danger text-center'>";
                                echo "<h4><i class='fas fa-exclamation-triangle'></i> Registro no disponible</h4>";
                                echo "<p>El período de registro no está activo en este momento.</p>";
                                
                                if (!empty($fecha_inicio) && !empty($fecha_fin)) {
                                    echo "<p><strong>Período de registro:</strong><br>";
                                    echo "Desde: " . date('d/m/Y', strtotime($fecha_inicio)) . "<br>";
                                    echo "Hasta: " . date('d/m/Y', strtotime($fecha_fin)) . "</p>";
                                    
                                    if ($fecha_actual < $fecha_inicio) {
                                        echo "<p class='text-info'>El registro iniciará el " . date('d/m/Y', strtotime($fecha_inicio)) . "</p>";
                                    } elseif ($fecha_actual > $fecha_fin) {
                                        echo "<p class='text-warning'>El período de registro finalizó el " . date('d/m/Y', strtotime($fecha_fin)) . "</p>";
                                    }
                                }
                                
                                echo "<p><small>Fecha actual: " . date('d/m/Y H:i') . "</small></p>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
    
    <?php
    // Incluyendo pie de página
    include '../Modulos/footer.php';
    ?>

</div>

</body>
</html>