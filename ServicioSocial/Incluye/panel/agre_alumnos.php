<?php
session_start();  // Inicia la sesión

include '../../Modulos/conexion.php';  // Incluir la conexión a la base de datos

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="../../js/scripts.js"></script>
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h3 class="mt-4">Agregar Alumno</h3>
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="alumnForm" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="Genero" class="form-label">Género</label>
                                    <select class="form-select" id="Genero" name="Genero" required>
                                        <option value="">Selecciona un servicio</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="numero_control" class="form-label">Número de Control</label>
                                    <input type="text" class="form-control" id="numero_control" name="numero_control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="Semestre" class="form-label">Semestre</label>
                                    <select class="form-select" id="Semestre" name="Semestre" aria-label="Semestre">
                                        <?php
                                        echo "<option selected disabled>Seleccione semestre</option>";
                                        $num_semestres = (strpos(strtolower($carrera), 'virtual') !== false) ? 15 : 15;
                                        
                                        for ($i = 1; $i <= $num_semestres; $i++) {
                                            echo "<option value='$i'>$i Semestre</option>";
                                        } ?>
                                    </select>
                                </div>
                                    <div class="col-md-4">
        <label for="Créditos" class="form-label">Créditos</label>
        <input type="number" class="form-control" id="Créditos" name="Creditos" placeholder="Ej. 200" required>
    </div>
                                <div class="col-md-4">
                                    <label for="carrera" class="form-label">Carrera</label>
                                    <select class="form-select" id="carrera" name="carrera" required>
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
                                </div>
                                <div class="col-md-4">
                                    <label for="Modalidad" class="form-label">Modalidad</label>
                                    <select class="form-select" id="Modalidad" name="Modalidad" aria-label="Modalidad">
                                        <option selected disabled>Seleccione Modalidad</option>
                                        <option value="P">Presencial</option>
                                        <option value="V">Virtual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="Estatus" class="form-label">Estatus</label>
                                    <select class="form-select" id="Estatus" name="Estatus" aria-label="Estatus">
                                        <option selected disabled>Seleccione Estatus</option>
                                        <option value="Candidato">Candidato</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="Correo" class="form-label">Correo</label>
                                    <input type="email" class="form-control" id="Correo" name="Correo" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="Turno" class="form-label">Turno</label>
                                    <select class="form-select" id="Turno" name="Turno" aria-label="Turno" required>
                                        <option selected disabled>Seleccione turno</option>
                                        <option value="Mañana">(Matutino) Mañana</option>
                                        <option value="Tarde">(Vespertino) Tarde</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="direccion_calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="direccion_calle" name="direccion_calle" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_Numero_Externo" class="form-label">Número Externo</label>
                                    <input type="text" class="form-control" id="direccion_Numero_Externo" name="direccion_Numero_Externo" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_Numero_Interno" class="form-label">Número Interno</label>
                                    <input type="text" class="form-control" id="direccion_Numero_Interno" name="direccion_Numero_Interno" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="Colonia" class="form-label">Colonia</label>
                                    <input type="text" class="form-control" id="Colonia" name="Colonia" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="Codigo_Postal" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="Codigo_Postal" name="Codigo_Postal" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_municipio" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="direccion_municipio" name="direccion_municipio" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="direccion_estado" name="direccion_estado" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../../Modulos/footer.php'; ?>
    </div>
 	<script src="../../js/scripts.js"></script>
  
    <script>
    $(document).ready(function () {
        $("#alumnForm").on("submit", function (e) {
            e.preventDefault(); // Evitar el comportamiento predeterminado del formulario

            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "/../..Modulos/agre_alum.php",
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
                                // Redirigir a la página deseada después de confirmar
                                window.location.href = '../../Incluye/panel/agre_alumnos.php';
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud.',
                    });
                }
            });
        });
    });
    </script>
        
</body>
</html>
