<?php
    /** Verificar si esta logeado */
    include 'is_logged.php'; 
    /** Conectar a la DB */
    require_once "../db.php";
    require_once "../php_conexion.php";
   
    require_once "../moneda.php";

    $simbolo_moneda = obtener_moneda();

    $date = new DateTime('now', new DateTimeZone('America/Asuncion'));
    $anio = $date->format('y');
    $total_compras = 0;
    $fecha = "$anio";

    $obtener_compras = mysqli_query($conexion, "SELECT monto_factura FROM facturas_compras WHERE DATE(fecha_factura) LIKE '%$fecha%'");

    while($row = mysqli_fetch_array($obtener_compras)){
        $total_compras += $row['monto_factura'];
    }
    echo formatear_moneda($simbolo_moneda, $total_compras);
?>