<?php
    /** Verificar si esta logeado */
    include 'is_logged.php'; 
    /** Conectar a la DB */
    require_once "../db.php";
    require_once "../php_conexion.php";
   
    require_once "../moneda.php";

    $simbolo_moneda = obtener_moneda();

    $fin_semana    = new DateTime('now', new DateTimeZone('America/Asuncion'));
    $inicio_semana = $fin_semana->format('d') - 7;
    $mes = $fin_semana->format('m');
    $total_compras = 0;

    if($inicio_semana <= 0) {
        $ultimo_dia_mes_anterior = new DateTime('last day of last month', new DateTimeZone('America/Asuncion'));

        $inicio_semana = $ultimo_dia_mes_anterior->format('d') + $inicio_semana;
        $mes = $ultimo_dia_mes_anterior->format('m');
    }

    while ($inicio_semana != $fin_semana->format('d')) {
        $fecha = date("Y-$mes-$inicio_semana");
        $obtener_compras = mysqli_query($conexion, "SELECT monto_factura FROM facturas_compras WHERE DATE(fecha_factura) = '$fecha'");

        while($row = mysqli_fetch_array($obtener_compras)){
            $total_compras += $row['monto_factura'];
        }
        $inicio_semana++;

        if(isset($ultimo_dia_mes_anterior)) {
            if($inicio_semana > $ultimo_dia_mes_anterior->format('d')) {
                $inicio_semana = 1;
                $mes = $fin_semana->format('m');
            }
        }
    }
    echo formatear_moneda($simbolo_moneda, $total_compras);
?>