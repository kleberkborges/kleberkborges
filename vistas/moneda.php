<?php
    function get_row ($table, $row, $id, $equal) {
        global $conexion;
        $query = mysqli_query($conexion, "SELECT $row FROM $table WHERE $id='$equal'");
        $rw    = mysqli_fetch_array($query);
        $value = $rw[$row];
        return $value;
    }
    /*--------------------------------------------------------------*/
    /* OBTENER MONEDA DE LA BASE DE DATOS
    /*--------------------------------------------------------------*/
    function obtener_moneda () {
        $moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
        return $moneda;
    }

    /*--------------------------------------------------------------*/
    /* FORMATEAR MONEDA
    /*--------------------------------------------------------------*/
    function formatear_moneda ($moneda, $numero) {
        if($moneda=='Gs.'){
            if($numero != 0) {
                return number_format($numero, 0, '', '.');
            } else {
                return number_format($numero, 3, '.', '.');
            }
        } else {
            return number_format($numero, 2);
        }
    }

    /*--------------------------------------------------------------*/
    /* OBTENER CODIGO DE LA MONEDA ACTUAL DE LA BASE DE DATOS
    /*--------------------------------------------------------------*/
    function obtener_codigo_moneda_actual () {
        $moneda = obtener_moneda();
        global $conexion;
        $query = mysqli_query($conexion, "SELECT id, code FROM currencies WHERE symbol = '$moneda'");
        $row = mysqli_fetch_assoc($query);
        mysqli_free_result($query);
        return $row;
    }

    /*--------------------------------------------------------------*/
    /* OBTENER CODIGOS DE LAS MONEDAS DE LA BASE DE DATOS
    /*--------------------------------------------------------------*/
    function obtener_codigos_monedas () {
        global $conexion;
        $query = mysqli_query($conexion, "SELECT id, code FROM currencies");
        $rows = [];
        while($row = mysqli_fetch_assoc($query)){
            $rows[] = $row;
        }
        mysqli_free_result($query);
        return $rows;
    }

    /*--------------------------------------------------------------*/
    /* OBTENER TODAS LAS MONEDAS
    /*--------------------------------------------------------------*/
    function obtener_monedas () {
        global $conexion;
        $query = mysqli_query($conexion, "SELECT symbol FROM currencies ORDER BY id DESC");
        $rows = [];
        while($row = mysqli_fetch_assoc($query)){
            $rows[] = $row;
        }
        mysqli_free_result($query);
        return $rows;
    }

    /*--------------------------------------------------------------*/
    /* CONVERTIR MONEDAS
    /*--------------------------------------------------------------*/
    function convertir_moneda ($moneda_actual, $moneda_a_convertir, $valor_a_convertir, $cambio_moneda) {
        // Cuando la moneda a convertir no es guaraní, elimina los puntos
        if ($moneda_a_convertir != 'Gs.') {
            return ($valor_a_convertir / $cambio_moneda);
        }

        else if ($moneda_actual == $moneda_a_convertir) {
            return $valor_a_convertir;
        }
    }

    /*--------------------------------------------------------------*/
    /* OBTENER LAS COTIZACIONES DEL DIA
    /*--------------------------------------------------------------*/
    function obtener_cotizaciones () {
        global $conexion;
        $moneda = obtener_moneda();
        $query = mysqli_query($conexion, "SELECT cotizacion, symbol FROM currencies ORDER BY id DESC");
        $rows = [];
        while($row = mysqli_fetch_assoc($query)){
            $rows[] = $row;
        }
        mysqli_free_result($query);
        return $rows;
    }

?>