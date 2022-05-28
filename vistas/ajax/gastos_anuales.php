<?php
    /** Verificar si esta logeado */
    include 'is_logged.php'; 
    /** Conectar a la DB */
    require_once "../db.php";
    require_once "../php_conexion.php";
   
    require_once "../moneda.php";

    $simbolo_moneda = obtener_moneda();

    $date = new DateTime('now', new DateTimeZone('America/Asuncion'));
    $anio = $date->format('Y');
    $total_gastos = 0;
    $fecha = "$anio";

    $obtener_gastos = mysqli_query($conexion, "SELECT SUM(monto) AS 'monto' FROM egresos WHERE YEAR(fecha_added) = '$fecha'");

    $gastos = mysqli_fetch_assoc($obtener_gastos);
    $total_gastos += $gastos['monto'];

    echo formatear_moneda($simbolo_moneda, $total_gastos);
?>