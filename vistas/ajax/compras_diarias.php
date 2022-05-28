<?php
    /** Verificar si esta logeado */
    include 'is_logged.php'; 
    /** Conectar a la DB */
    require_once "../db.php";
    require_once "../php_conexion.php";
   
    require_once "../moneda.php";
    
    $date = new DateTime('now', new DateTimeZone('America/Asuncion'));
    $mes  = $date->format('m');
    $anio = $date->format('Y');
    
    $dia = '01';
    $dia_final   = $date->format('d');

    $total_compras = 0;
    $i = 0;
    $fechas = [];
    $totales = [];
    while ($dia <= $dia_final) {
        $fecha = "$anio-$mes-$dia";
        $compras_mes_actual = mysqli_query($conexion, "SELECT monto_factura FROM facturas_compras WHERE DATE(fecha_factura) = '$fecha'");

        while($row = mysqli_fetch_array($compras_mes_actual)){
            $total_compras += $row['monto_factura'];
        }
        $fecha = new DateTime($fecha);
        $fechas[] = $fecha->format('d-m-Y');
        $totales[$i] = $total_compras;
        $total_compras = 0;
        $dia++;
        $i++;
    }
    $data = [
        "totales" => $totales,
        "fechas" => $fechas
    ];
    echo json_encode($data);
    
?>