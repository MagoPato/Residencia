<?php
session_start();
include '../../Seguridad/control_sesion.php';

// Instalar PhpSpreadsheet con: composer require phpoffice/phpspreadsheet
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Código PHP para procesar los filtros y obtener datos
$estudiantes = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['Carrera']) || isset($_GET['estatus']) || isset($_GET['genero']))) {
    // Incluir conexión a la base de datos
    include '../../Modulos/conexion.php';
    
    // Inicializar variables
    $where_conditions = [];
    $params = [];
    $types = '';
    
    // Procesar filtro de carreras
    if (isset($_GET['Carrera']) && !empty($_GET['Carrera'])) {
        $carreras = $_GET['Carrera'];
        
        // Si no se selecciona "Todas", filtrar por carreras específicas
        if (!in_array('Todas', $carreras)) {
            $placeholders = str_repeat('?,', count($carreras) - 1) . '?';
            $where_conditions[] = "a.Carrera IN ($placeholders)";
            $params = array_merge($params, $carreras);
            $types .= str_repeat('s', count($carreras));
        }
    }
    
    // Procesar filtro de estatus
    if (isset($_GET['estatus']) && !empty($_GET['estatus'])) {
        $estatus_array = $_GET['estatus'];
        
        if (!in_array('todos', $estatus_array)) {
            // Convertir los valores a los que están en la base de datos
            $estatus_bd = [];
            foreach ($estatus_array as $est) {
                if ($est == 'activo') {
                    $estatus_bd[] = 'Candidato'; // Según tu BD, parece que usan "Candidato"
                } elseif ($est == 'inactivo') {
                    $estatus_bd[] = 'Baja'; // Según tu BD
                }
            }
            
            if (!empty($estatus_bd)) {
                $placeholders = str_repeat('?,', count($estatus_bd) - 1) . '?';
                $where_conditions[] = "a.Estatus IN ($placeholders)";
                $params = array_merge($params, $estatus_bd);
                $types .= str_repeat('s', count($estatus_bd));
            }
        }
    }
    
    // Procesar filtro de género
    if (isset($_GET['genero']) && !empty($_GET['genero'])) {
        $generos = $_GET['genero'];
        
        if (!in_array('todos', $generos)) {
            // Convertir a los valores de la base de datos
            $generos_bd = [];
            foreach ($generos as $gen) {
                if ($gen == 'femenino') {
                    $generos_bd[] = 'F';
                } elseif ($gen == 'masculino') {
                    $generos_bd[] = 'M';
                }
            }
            
            if (!empty($generos_bd)) {
                $placeholders = str_repeat('?,', count($generos_bd) - 1) . '?';
                $where_conditions[] = "a.Genero IN ($placeholders)";
                $params = array_merge($params, $generos_bd);
                $types .= str_repeat('s', count($generos_bd));
            }
        }
    }
    
    // Construir la consulta SQL simplificada - solo datos básicos del alumno
    $sql = "SELECT 
                a.No_Control, 
                a.Nombre, 
                a.Apellido_P, 
                a.Apellido_M, 
                a.Telefono, 
                a.Semestre, 
                CASE 
                    WHEN a.Modalidad = 'P' THEN 'Presencial'
                    WHEN a.Modalidad = 'V' THEN 'Virtual'
                    ELSE a.Modalidad 
                END as Modalidad_Desc,
                a.Correo,
                CASE 
                    WHEN a.Turno = 'M' THEN 'Matutino'
                    WHEN a.Turno = 'V' THEN 'Vespertino'
                    ELSE a.Turno 
                END as Turno_Desc,
                a.Estatus,
                CASE 
                    WHEN a.Genero = 'M' THEN 'Masculino'
                    WHEN a.Genero = 'F' THEN 'Femenino'
                    ELSE a.Genero 
                END as Genero_Desc,
                a.Carrera,
                a.Créditos,
                -- Información del servicio (opcional - solo si existe inscripción)
                COALESCE(s.Programa, 'Sin servicio asignado') as Servicio_Programa,
                COALESCE(s.Departamento, 'N/A') as Servicio_Departamento,
                COALESCE(s.Actividades, 'N/A') as Servicio_Actividades,
                COALESCE(s.Tipo, 'N/A') as Servicio_Tipo,
                COALESCE(r.nombre, 'N/A') as Responsable_Nombre,
                COALESCE(r.puesto, 'N/A') as Responsable_Puesto,
                COALESCE(d.Nombre, 'N/A') as Dependencia_Nombre
            FROM alumno a
            LEFT JOIN inscripciones i ON a.No_Control = i.Id_Alumno
            LEFT JOIN servicio s ON i.Id_Servicio = s.Id
            LEFT JOIN responsable r ON s.Id_Responsable = r.id
            LEFT JOIN dependencia d ON s.Id_Dependencia = d.Id";
    
    // Agregar condiciones WHERE si existen
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(' AND ', $where_conditions);
    }
    
    $sql .= " ORDER BY a.No_Control";
    
    try {
        // Preparar y ejecutar la consulta usando MySQLi
        if (!empty($params)) {
            $stmt = $enlace->prepare($sql);
            $stmt->bind_param($types, ...$params);
        } else {
            $stmt = $enlace->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Obtener todos los resultados
        $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
        
        $stmt->close();
        
        // Si se encontraron resultados, generar Excel automáticamente
        if (!empty($estudiantes)) {
            generarExcel($estudiantes);
        }
        
    } catch (Exception $e) {
        echo "Error en la consulta: " . $e->getMessage();
        $estudiantes = [];
    }
}

// Función para generar Excel
function generarExcel($estudiantes) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Estudiantes');
    
    // Definir encabezados
    $headers = [
        'A1' => 'No. Control',
        'B1' => 'Nombre',
        'C1' => 'Apellido Paterno',
        'D1' => 'Apellido Materno',
        'E1' => 'Teléfono',
        'F1' => 'Semestre',
        'G1' => 'Modalidad',
        'H1' => 'Correo',
        'I1' => 'Turno',
        'J1' => 'Estatus',
        'K1' => 'Género',
        'L1' => 'Carrera',
        'M1' => 'Créditos',
        'N1' => 'Programa Servicio',
        'O1' => 'Departamento Servicio',
        'P1' => 'Actividades Servicio',
        'Q1' => 'Tipo Servicio',
        'R1' => 'Responsable',
        'S1' => 'Puesto Responsable',
        'T1' => 'Dependencia'
    ];
    
    // Establecer encabezados
    foreach ($headers as $cell => $header) {
        $sheet->setCellValue($cell, $header);
    }
    
    // Estilo para encabezados
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '366092']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ]
        ]
    ];
    
    $sheet->getStyle('A1:T1')->applyFromArray($headerStyle);
    
    // Llenar datos
    $row = 2;
    foreach ($estudiantes as $estudiante) {
        $sheet->setCellValue('A' . $row, $estudiante['No_Control']);
        $sheet->setCellValue('B' . $row, $estudiante['Nombre']);
        $sheet->setCellValue('C' . $row, $estudiante['Apellido_P']);
        $sheet->setCellValue('D' . $row, $estudiante['Apellido_M']);
        $sheet->setCellValue('E' . $row, $estudiante['Telefono'] ?? '');
        $sheet->setCellValue('F' . $row, $estudiante['Semestre']);
        $sheet->setCellValue('G' . $row, $estudiante['Modalidad_Desc']);
        $sheet->setCellValue('H' . $row, $estudiante['Correo']);
        $sheet->setCellValue('I' . $row, $estudiante['Turno_Desc']);
        $sheet->setCellValue('J' . $row, $estudiante['Estatus']);
        $sheet->setCellValue('K' . $row, $estudiante['Genero_Desc']);
        $sheet->setCellValue('L' . $row, $estudiante['Carrera'] ?? '');
        $sheet->setCellValue('M' . $row, $estudiante['Créditos'] ?? '');
        $sheet->setCellValue('N' . $row, $estudiante['Servicio_Programa'] ?? '');
        $sheet->setCellValue('O' . $row, $estudiante['Servicio_Departamento'] ?? '');
        $sheet->setCellValue('P' . $row, $estudiante['Servicio_Actividades'] ?? '');
        $sheet->setCellValue('Q' . $row, $estudiante['Servicio_Tipo'] ?? '');
        $sheet->setCellValue('R' . $row, $estudiante['Responsable_Nombre'] ?? '');
        $sheet->setCellValue('S' . $row, $estudiante['Responsable_Puesto'] ?? '');
        $sheet->setCellValue('T' . $row, $estudiante['Dependencia_Nombre'] ?? '');
        $row++;
    }
    
    // Aplicar bordes a todos los datos
    $dataStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ]
        ]
    ];
    
    if ($row > 2) {
        $sheet->getStyle('A1:T' . ($row - 1))->applyFromArray($dataStyle);
    }
    
    // Autoajustar columnas
    foreach (range('A', 'T') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Generar archivo
    $writer = new Xlsx($spreadsheet);
    $filename = 'estudiantes_' . date('Y-m-d_H-i-s') . '.xlsx';
    
    // Headers para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}
?>

<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Servicio Social - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/estilos-interfaz.css" rel="stylesheet" />    
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
</head>

<body class="sb-nav-fixed">
    <?php include 'barra.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 mb-4 text-center">Consulta de Estudiantes</h1>
                
                <div class="card filter-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <form id="filtroForm" method="GET">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Carreras</label>                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería Ambiental" id="carreraIA">
                                        <label class="form-check-label" for="carreraIA">Ingeniería Ambiental</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería Electromecánica" id="carreraIEM">
                                        <label class="form-check-label" for="carreraIEM">Ingeniería Electromecánica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería Electrónica" id="carreraIE">
                                        <label class="form-check-label" for="carreraIE">Ingeniería Electrónica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería en Gestión Empresarial" id="carreraIGE">
                                        <label class="form-check-label" for="carreraIGE">Ingeniería en Gestión Empresarial</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería Industrial" id="carreraII">
                                        <label class="form-check-label" for="carreraII">Ingeniería Industrial</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería Mecatrónica" id="carreraIMCT">
                                        <label class="form-check-label" for="carreraIMCT">Ingeniería Mecatrónica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería en Sistemas Computacionales" id="carreraISC">
                                        <label class="form-check-label" for="carreraISC">Ingeniería en Sistemas Computacionales</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Ingeniería en Semiconductores" id="carreraISM">
                                        <label class="form-check-label" for="carreraISM">Ingeniería en Semiconductores</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Carrera[]" value="Todas" id="carreraAll">
                                        <label class="form-check-label fw-bold" for="carreraAll">Todas</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Estatus</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="estatus[]" id="activo" value="activo" />
                                        <label class="form-check-label" for="activo">Activo</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="estatus[]" id="inactivo" value="inactivo" />
                                        <label class="form-check-label" for="inactivo">Inactivo</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="estatus[]" id="todosEstatus" value="todos" />
                                        <label class="form-check-label fw-bold" for="todosEstatus">Todos</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Género</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="genero[]" id="femenino" value="femenino" />
                                        <label class="form-check-label" for="femenino">Femenino</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="genero[]" id="masculino" value="masculino" />
                                        <label class="form-check-label" for="masculino">Masculino</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="genero[]" id="todosGenero" value="todos" />
                                        <label class="form-check-label fw-bold" for="todosGenero">Todos</label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-file-excel me-2"></i>Descargar Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Mensajes informativos -->
                <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['Carrera']) || isset($_GET['estatus']) || isset($_GET['genero'])) && empty($estudiantes)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    No se encontraron resultados con los filtros seleccionados.
                </div>
                <?php else: ?>
                <div class="alert alert-secondary text-center">
                    <i class="fas fa-filter me-2"></i>
                    Selecciona los filtros y presiona "Descargar Excel" para generar el reporte.
                </div>
                <?php endif; ?>
            </div>
        </main>
        <br>
        <?php include '../../Modulos/footer.php'; ?>
    </div>
    
    <script>
        // JavaScript para manejar los checkboxes de "Todas"
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del checkbox "Todas" las carreras
            const carreraAll = document.getElementById('carreraAll');
            const carreraCheckboxes = document.querySelectorAll('input[name="Carrera[]"]:not(#carreraAll)');
            
            carreraAll.addEventListener('change', function() {
                if (this.checked) {
                    carreraCheckboxes.forEach(cb => cb.checked = false);
                }
            });
            
            carreraCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        carreraAll.checked = false;
                    }
                });
            });
            
            // Manejo del checkbox "Todos" los estatus
            const estatusAll = document.getElementById('todosEstatus');
            const estatusCheckboxes = document.querySelectorAll('input[name="estatus[]"]:not(#todosEstatus)');
            
            estatusAll.addEventListener('change', function() {
                if (this.checked) {
                    estatusCheckboxes.forEach(cb => cb.checked = false);
                }
            });
            
            estatusCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        estatusAll.checked = false;
                    }
                });
            });
            
            // Manejo del checkbox "Todos" los géneros
            const generoAll = document.getElementById('todosGenero');
            const generoCheckboxes = document.querySelectorAll('input[name="genero[]"]:not(#todosGenero)');
            
            generoAll.addEventListener('change', function() {
                if (this.checked) {
                    generoCheckboxes.forEach(cb => cb.checked = false);
                }
            });
            
            generoCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        generoAll.checked = false;
                    }
                });
            });
        });
    </script>
    
    <script src="../../js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            // Script para manejar el checkbox "Todas" en carreras
            $('#carreraAll').change(function() {
                if (this.checked) {
                    $('input[name="Carrera[]"]:not(#carreraAll)').prop('checked', false);
                }
            });

            // Si se selecciona otra carrera, desmarcar "Todas"
            $('input[name="Carrera[]"]:not(#carreraAll)').change(function() {
                if (this.checked) {
                    $('#carreraAll').prop('checked', false);
                }
            });

            // Script similar para estatus
            $('#todosEstatus').change(function() {
                if (this.checked) {
                    $('input[name="estatus[]"]:not(#todosEstatus)').prop('checked', false);
                }
            });

            $('input[name="estatus[]"]:not(#todosEstatus)').change(function() {
                if (this.checked) {
                    $('#todosEstatus').prop('checked', false);
                }
            });

            // Script similar para género
            $('#todosGenero').change(function() {
                if (this.checked) {
                    $('input[name="genero[]"]:not(#todosGenero)').prop('checked', false);
                }
            });

            $('input[name="genero[]"]:not(#todosGenero)').change(function() {
                if (this.checked) {
                    $('#todosGenero').prop('checked', false);
                }
            });
        });
    </script>
</body>
</html>