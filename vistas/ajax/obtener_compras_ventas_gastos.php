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

    /** OBTENER COMPRAS */
    $obtener_compras = mysqli_query($conexion, "SELECT SUM(monto_factura) AS 'monto' FROM facturas_compras WHERE YEAR(fecha_factura) = '$anio'");
    $total_compras = mysqli_fetch_assoc($obtener_compras);
    mysqli_free_result($obtener_compras);

    /** OBTENER VENTAS */
    $obtener_ventas = mysqli_query($conexion, "SELECT SUM(monto_factura) AS 'monto' FROM facturas_ventas WHERE YEAR(fecha_factura) = '$anio'");
    $total_ventas = mysqli_fetch_assoc($obtener_ventas);
    mysqli_free_result($obtener_ventas);

    /** OBTENER GASTOS */
    $obtener_gastos = mysqli_query($conexion, "SELECT SUM(monto) AS 'monto' FROM egresos WHERE YEAR(fecha_added) = '$anio'");
    $total_gastos = mysqli_fetch_assoc($obtener_gastos);
    mysqli_free_result($obtener_gastos);

    $data = [
        "labels" => [
            "Compras",
            "Ventas",
            "Gastos"
        ],
        "montos" => [
            $total_compras['monto'],
            $total_ventas['monto'],
            $total_gastos['monto']
        ]
    ];

    echo json_encode($data);
?>