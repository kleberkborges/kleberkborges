<?php
    require_once "../moneda.php";

    $moneda = obtener_moneda();
    $query = mysqli_query($conexion, "SELECT cotizacion_date FROM currencies WHERE symbol != '$moneda'");
    while ($rows = mysqli_fetch_assoc($query)) {
        $current_day = date('d');
        if($rows['cotizacion_date'] != $current_day){
            header('location: cotizacion.php');
        }
    }
