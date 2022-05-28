<?php
    /** Verificar si esta logeado */
    include 'is_logged.php'; 
    /** Conectar a la DB */
    require_once "../db.php";
    require_once "../php_conexion.php";
   
    require_once "../moneda.php";

    $simbolo_moneda = obtener_moneda();

    $date = new DateTime('now', new DateTimeZone('America/Asuncion'));
    $mes  = $date->format('m');
    $anio = $date->format('y');
    $fecha = "$anio-$mes-";

    $obtener_gastos = mysqli_query($conexion, "SELECT SUM(monto) AS 'monto' FROM egresos WHERE DATE(fecha_added) LIKE '%$fecha%'");

    $total_gastos = mysqli_fetch_assoc($obtener_gastos);

    echo formatear_moneda($simbolo_moneda, $total_gastos['monto']);
?>