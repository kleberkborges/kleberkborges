<?php

require_once '../db.php';
require_once '../php_conexion.php';
require_once '../moneda.php';

$moneda = obtener_moneda();
if (isset($_REQUEST['utilidad'])) {
    $utilidad     = intval($_REQUEST['utilidad']);
    $costo        = $_REQUEST['costo'];

    $costo        = str_replace('.', '', $costo);
    $costo        = (int)$costo;

    $utilidad     = ($costo * $utilidad) / 100;
    $precio_venta = $costo + $utilidad;
    $precio_venta = formatear_moneda($moneda, $precio_venta);

    $price[] = array('precio' => $precio_venta);
    //Creamos el JSON
    $json_string = json_encode($price);
    echo $json_string;
} elseif (isset($_REQUEST['mod_utilidad'])) {
    $utilidad     = intval($_REQUEST['mod_utilidad']);
    $costo        = $_REQUEST['mod_costo'];

    $costo        = str_replace('.', '', $costo);
    $costo        = (int)$costo;

    $utilidad     = ($costo * $utilidad) / 100;
    $precio_venta = $costo + $utilidad;
    $precio_venta = formatear_moneda($moneda, $precio_venta);

    $price[] = array('mod_precio' => $precio_venta);
    //Creamos el JSON
    $json_string = json_encode($price);
    echo $json_string;
}
