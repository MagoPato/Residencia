<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> Servicio Social -  Administrador </title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <?php
    // contenido.php
    include 'barra.php';
    ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Consulta de Estudiantes</h1>
                <div class="card mb-4">
                    <div class="card-body">
                        <form>
                            <label>Filtrar por: </label></p>
                        <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="numero_control" class="form-label">Numero de Control</label>
                                    <input type="text" class="form-control" id="numero_control" name="numero_control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="carrera" class="form-label">Carrera</label>
                                    <!-- recordar quitar el required-->
                                    <select class="form-select" id="carrera" name="carrera" required>
                                        <option value="">Selecciona una carrera</option>
                                        <option value="Ingeniería Ambiental">Ingeniería Ambiental</option>
                                        <option value="Ingeniería Electromecánica">Ingeniería Electromecánica</option>
                                        <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                                        <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
                                        <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                                        <option value="Ingeniería Mecatrónica">Ingeniería Mecatrónica</option>
                                        <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
                                        <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales virtual</option>
                                        <option value="Ingeniería en Semiconductores">Ingeniería en Semiconductores</option>
                                    </select>
                                </div>
                        </div>
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
                                    <label for="genero" class="form-label">Género</label>
                                    <input type="text" class="form-control" id="genero" name="genero" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="estatus" class="form-label">Estatus</label>
                                    <input type="text" class="form-control" id="estatus" name="estatus" required>
                                </div>
                                
                        </div>
                        <div class="row mb-3">
                                <div class="row mb-3">
                                    <label for="ordenar" class="form-label">Ordenar por:</label></p>
                                </div>
                            <div class="col-md-8">
                               <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ordenar" id="opcion1" value="opcion1">
                                    <label class="form-check-label" for="opcion1">Numero de Control</label>
                               </div>
                               <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ordenar" id="opcion2" value="opcion2">
                                    <label class="form-check-label" for="opcion2">Nombre(s) </label>
                               </div>
                               <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ordenar" id="opcion3" value="opcion3">
                                    <label class="form-check-label" for="opcion3">Carrera</label>
                               </div>
                              <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ordenar" id="opcion4" value="opcion4">
                                    <label class="form-check-label" for="opcion4">Estatus</label>
                               
                             </div>
                            </div>

                    
                        </div>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php
        // contenido.php
        include '../../modulos/footer.php';
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../../js/datatables-simple-demo.js"></script>
</body>

</html>