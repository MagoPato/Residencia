<?php
session_start();  // Inicia la sesión
include '../../Seguridad/control_sesion.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="TecNM" />
    <meta name="author" content="" />
    <title>Servicio Social - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <style>
        #estudiantesTable th, #estudiantesTable td {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-4 text-center fw-bold">Panel de Administración</h2>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Listado de Alumnos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" id="buscarAlumno" class="form-control" placeholder="Buscar alumno...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="estudiantesTable" class="table table-hover table-striped table-bordered align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th scope="col">No. Control</th>
                                        <th scope="col">Nombres</th>
                                        <th scope="col">Apellido Paterno</th>
                                        <th scope="col">Apellidos Maternos</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Semestre</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Turno</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Sexo</th>
                                        <th scope="col">Carrera</th>
                                        <th scope="col">Créditos</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="resultadosTabla">
                                    <!-- Datos dinámicos -->
                                </tbody>
                            </table>
                        </div>

                        <div id="loadingIndicator" class="text-center d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando datos...</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Mostrando <span id="contador">0</span> alumnos
                            </div>
                            <nav aria-label="Paginación">
                                <ul id="paginacion" class="pagination pagination-sm justify-content-end mb-0">
                                    <!-- Botones generados dinámicamente -->
                                </ul>
                            </nav>
                        </div>

                        <div class="text-center mt-3">
                            <button id="consultaBtn" class="btn btn-light btn-sm">
                                <i class="fas fa-sync-alt me-1"></i>Actualizar Datos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../../Modulos/footer.php'; ?>
    </div>

    <script>
    $(document).ready(function () {
        const alumnosPorPagina = 10;
        let datosFiltrados = [];

        function mostrarPagina(pagina) {
            const inicio = (pagina - 1) * alumnosPorPagina;
            const fin = inicio + alumnosPorPagina;
            $('#resultadosTabla tr').hide();
            $('#resultadosTabla tr').slice(inicio, fin).show();
        }

        function generarPaginacion(total) {
            const totalPaginas = Math.ceil(total / alumnosPorPagina);
            let pagHtml = '';

            pagHtml += `<li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>`;

            for (let i = 1; i <= totalPaginas; i++) {
                pagHtml += `<li class="page-item ${i === 1 ? 'active' : ''}"><a class="page-link" href="#">${i}</a></li>`;
            }

            pagHtml += `<li class="page-item"><a class="page-link" href="#">Siguiente</a></li>`;

            $('#paginacion').html(pagHtml);

            $('#paginacion .page-link').click(function (e) {
                e.preventDefault();
                const texto = $(this).text();
                let paginaActual = parseInt($('#paginacion .active a').text());
                let nuevaPagina = paginaActual;

                if (texto === 'Siguiente') nuevaPagina++;
                else if (texto === 'Anterior') nuevaPagina--;
                else nuevaPagina = parseInt(texto);

                if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;

                $('#paginacion .page-item').removeClass('active');
                $(`#paginacion .page-item:has(a:contains(${nuevaPagina}))`).addClass('active');
                mostrarPagina(nuevaPagina);
            });
        }

        function cargarDatos() {
            $('#loadingIndicator').removeClass('d-none');
            $.ajax({
                url: '../../Modulos/modulo_consulta.php',
                type: 'GET',
                success: function (response) {
                    $('#loadingIndicator').addClass('d-none');
                    if (response && response.trim() !== '') {
                        $('#resultadosTabla').html(response);
                        const numFilas = $('#resultadosTabla tr').length;
                        $('#contador').text(numFilas);
                        datosFiltrados = $('#resultadosTabla tr');
                        generarPaginacion(numFilas);
                        mostrarPagina(1);
                    } else {
                        $('#resultadosTabla').html('<tr><td colspan="13" class="text-center py-4"><div class="alert alert-info mb-0"><i class="fas fa-info-circle me-2"></i>No se encontraron registros de alumnos.</div></td></tr>');
                        $('#contador').text('0');
                        $('#paginacion').empty();
                    }
                },
                error: function () {
                    $('#loadingIndicator').addClass('d-none');
                    $('#resultadosTabla').html('<tr><td colspan="13" class="text-center py-4"><div class="alert alert-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Error al realizar la consulta. Intente nuevamente.</div></td></tr>');
                    $('#contador').text('0');
                    $('#paginacion').empty();
                }
            });
        }

        $('#consultaBtn').click(function () {
            cargarDatos();
        });

        $('#buscarAlumno').on('keyup', function () {
            const valor = $(this).val().toLowerCase();
            datosFiltrados.each(function () {
                const match = $(this).text().toLowerCase().indexOf(valor) > -1;
                $(this).toggle(match);
            });

            const visibles = datosFiltrados.filter(':visible').length;
            $('#contador').text(visibles);
            generarPaginacion(visibles);
            mostrarPagina(1);
        });

        // Acciones con botones
        $(document).on('click', '.btn-primary', function () {
            const id = $(this).data('id');
            window.location.href = `ver_alumno.php?id=${id}`;
        });

        $(document).on('click', '.btn-success', function () {
            const id = $(this).data('id');
            window.location.href = `editar_alumno.php?id=${id}`;
        });

        $(document).on('click', '.btn-danger', function () {
            const id = $(this).data('id');
            if (confirm("¿Estás seguro de eliminar este registro?")) {
                window.location.href = `eliminar_alumno.php?id=${id}`;
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

        // 🚀 Carga automática al entrar
        cargarDatos();
    });
    </script>
</body>
</html>
