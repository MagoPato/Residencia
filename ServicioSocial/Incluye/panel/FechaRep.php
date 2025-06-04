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
    <meta name="description" content="Sisteme de Servicio Social" />
    <meta name="author" content="TecNL" />
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
                <h1 class="mt-4">Agregar Fechas de </h1>
                <div class="card mb-4">
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="numero_control" class="form-label">Número de Control</label>
                                    <input type="text" class="form-control" id="numero_control" name="numero_control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="carrera" class="form-label">Carrera</label>
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
                                    <label for="direccion_calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="direccion_calle" name="direccion_calle" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_numero_exterior" class="form-label">Número Exterior</label>
                                    <input type="text" class="form-control" id="direccion_numero_exterior" name="direccion_numero_exterior" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_numero_interior" class="form-label">Número Interior</label>
                                    <input type="text" class="form-control" id="direccion_numero_interior" name="direccion_numero_interior">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="direccion_colonia" class="form-label">Colonia</label>
                                    <input type="text" class="form-control" id="direccion_colonia" name="direccion_colonia" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_codigo_postal" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="direccion_codigo_postal" name="direccion_codigo_postal" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion_municipio" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="direccion_municipio" name="direccion_municipio" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="direccion_estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="direccion_estado" name="direccion_estado" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="servicio" class="form-label">Servicio</label>
                                    <select class="form-select" id="servicio" name="servicio" required>
                                        <option value="">Selecciona un servicio</option>
                                        <option value="Servicio Social">Servicio Social</option>
                                        <option value="Prácticas Profesionales">Prácticas Profesionales</option>
                                        <!-- Agrega más opciones según tus necesidades -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="turno" class="form-label">Turno</label>
                                    <select class="form-select" id="turno" name="turno" required>
                                        <option value="">Selecciona un turno</option>
                                        <option value="Mañana">Mañana</option>
                                        <option value="Tarde">Tarde</option>
                                        <!-- Agrega más opciones según tus necesidades -->
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="horario" class="form-label">Horario</label>
                                    <select class="form-select" id="horario" name="horario" required>
                                        <option value="">Selecciona un horario</option>
                                        <option value="Turno1">8:00 a.m. - 12:00 p.m.</option>
                                        <option value="Turno2">9:00 a.m. - 13:00 p.m. </option>
                                        <option value="Turno3">10:00 a.m. - 14:00 p.m. </option>
                                        <option value="Turno4">11:00 a.m. - 15:00 p.m. </option>
                                        <option value="Turno5">12:00 p.m. - 16:00 p.m. </option>
                                        <option value="turno6">13:00 p.m. - 17:00 p.m. </option>
                                        <option value="Turno7">14:00 p.m. - 18:00 p.m. </option>
                                        <option value="Turno8">15:00 p.m.- 19:00 p.m.</option>
                                        <option value="Turno9">16:00 p.m.- 20:00 p.m.</option>  
                                        <option value="Turno10">17:00 p.m. - 21:00 p.m. </option>
                                        <option value="Turno11">18:00 p.m. - 22:00 p.m.</option>
                                        <!-- Agrega más opciones según tus necesidades -->
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php
        // contenido.php
        include '../../Modulos/footer.php';
        ?>
    </div>
    <script src="../../js/scripts.js"></script>
    <script src="../../js/datatables-simple-demo.js"></script>
</body>

</html>