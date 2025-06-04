<?php
$sql = "SELECT alumno.No_Control, alumno.Nombre, alumno.Apellido_P, alumno.Apellido_M, alumno.Carrera, servicio.Programa, servicio.Tipo 
FROM alumno 
INNER JOIN servicio ON alumno.Id_Servicio = servicio.Id
WHERE 1=1"; 

$params = [];

if ($genero) {
$sql .= " AND alumno.Genero = :genero";
$params[':genero'] = $genero;
}
if ($carrera) {
$sql .= " AND alumno.Carrera = :carrera";
$params[':carrera'] = $carrera;
}
if ($tipo_servicio) {
$sql .= " AND servicio.Tipo = :tipo_servicio";
$params[':tipo_servicio'] = $tipo_servicio;
}
?>