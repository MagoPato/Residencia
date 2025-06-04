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
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> Servicio Social - Administrador</title>
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
                <h1 class="mt-4">Agregar Programa de Servicio</h1>
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="programaForm">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nombre_dependencia" class="form-label">Nombre de la Dependencia</label>
                                    <input type="text" class="form-control" id="nombre_dependencia" name="nombre_dependencia" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="Nombre_responsable" class="form-label">Nombre del responsable de la Dependencia</label>
                                    <input type="text" class="form-control" id="Nombre_responsable" name="Nombre_responsable" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="Apellido_responsable" class="form-label">Apellido del responsable de la Dependencia</label>
                                    <input type="text" class="form-control" id="Apellido_responsable" name="Apellido_responsable" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="puesto_responsable" class="form-label">Puesto del responsable de la Dependencia</label>
                                    <input type="text" class="form-control" id="puesto_responsable" name="puesto_responsable" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="nombre_programa" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="actividades" class="form-label">Actividades</label>
                                    <textarea class="form-control" id="actividades" name="actividades" rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="EncargadoNom" class="form-label">Nombre de Encargado</label>
                                    <input type="text" class="form-control" id="EncargadoNom" name="EncargadoNom" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="EncargadoApe" class="form-label">Apellido de Encargado</label>
                                    <input type="text" class="form-control" id="EncargadoApe" name="EncargadoApe" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="PuestoEn" class="form-label">Puesto de Encargado</label>
                                    <input type="text" class="form-control" id="PuestoEn" name="PuestoEn" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="departamento" class="form-label">Departamento</label>
                                    <input type="text" class="form-control" id="departamento" name="departamento" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
                                    <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                                        <option value="">Selecciona el tipo de servicio</option>
                                        <option value="EpA">Educación para adultos</option>
                                        <option value="ACv">Actividades Civicas</option>
                                        <option value="DSus">Desarrollo sustentable</option>
                                        <option value="DesCom">Desarrollo de Comunidad</option>
                                        <option value="ACl">Actividades Culturales</option>
                                        <option value="ApSld">Apoyo a la Salud</option>
                                        <option value="ActDep">Actividades Deportivas</option>
                                        <option value="MedAmb">Medio Ambiente</option>
                                        <option value="Otrs">Otro</option>
                                    </select>
                                </div>  
                            </div>
                            <button type="button" class="btn btn-primary" id="submitBtn">Agregar</button>
                        </form>
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

                let isValid = true;

                // Verificar inputs, textareas y selects requeridos
                $("#programaForm").find("input[required], textarea[required], select[required]").each(function () {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass("is-invalid");
                    } else {
                        $(this).removeClass("is-invalid");
                    }
                });

                // Validar campo select específicamente
                const tipoServicio = $("#tipo_servicio").val();
                if (!tipoServicio) {
                    isValid = false;
                    $("#tipo_servicio").addClass("is-invalid");
                } else {
                    $("#tipo_servicio").removeClass("is-invalid");
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Formulario Incompleto',
                        text: 'Por favor, completa todos los campos antes de continuar.',
                    });
                    return;
                }

                const formData = $("#programaForm").serialize();

                $.ajax({
                    type: "POST",
                    url: "../../Modulos/registro_programa.php",
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
                                    window.location.href = '../../Incluye/admin/agre_cupo.php';
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
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al enviar los datos.',
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
