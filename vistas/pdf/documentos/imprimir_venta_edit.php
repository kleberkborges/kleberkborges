<?php
include '../../ajax/is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
?>
<style type="text/css" media="print">
    #Imprime {
        height: auto;
        width: 377px;
        margin: 0px;
        padding: 0px;
        float: left;
        font-family: "Comic Sans MS", cursive;
        font-size: 7px;
        font-style: normal;
        line-height: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        color: #000;
    }
    @page{
        margin: 0;
    }
</style>
<?php
/* Connect To Database*/
require_once "../../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include "../../funciones.php";
$id_factura = $_POST['id_factura'];
/*Datos de la empresa*/
$sql           = mysqli_query($conexion, "SELECT * FROM perfil");
$rw            = mysqli_fetch_array($sql);
$moneda        = $rw["moneda"];
$bussines_name = $rw["nombre_empresa"];
$giro          = $rw["giro_empresa"];
$fiscal        = $rw["fiscal_empresa"];
$address       = $rw["direccion"];
$city          = $rw["ciudad"];
$phone         = $rw["telefono"];
$email         = $rw["email"];
$logo_url      = $rw["logo_url"];
/*Fin datos empresa*/
$simbolo_moneda = obtener_moneda();
$sql_factura    = mysqli_query($conexion, "SELECT * FROM facturas_ventas, users WHERE facturas_ventas.id_vendedor=users.id_users AND facturas_ventas.id_factura='" . $id_factura . "'");
$count          = mysqli_num_rows($sql_factura);
$rw_factura     = mysqli_fetch_array($sql_factura);
$nombre_users   = $rw_factura['nombre_users'];
$fecha_factura  = date("d/m/Y", strtotime($rw_factura['fecha_factura']));
$hora_factura   = date('H:i:s', strtotime($rw_factura['fecha_factura']));
$condiciones    = $rw_factura['condiciones'];
$numero_factura = $rw_factura['numero_factura'];
$recibido       = $rw_factura['dinero_resibido_fac'];
?>

<page pageset='new' backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm' footer='page'>
    <table width="175px" style="font-size:12px; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif" border="0" >
        <tr>
            <td colspan="3">
                <div align="center" style="font-size:22px"><strong><?php echo $bussines_name; ?></strong><br></div>
                <div align="center" style="font-size:16px"><strong><?php echo $giro; ?></strong><br></div>
                <div align="center" style="font-size:16px"><strong>RUC / Cédula: <?php echo $fiscal; ?></strong><br></div>
                <div align="center" style="font-size:14px"><strong><?php echo $address; ?></strong><br></div>
                <div align="center" style="font-size:14px"><strong><?php echo $city ?></strong><br></div>
                <div align="center" style="font-size:14px"><strong>Tel: </strong> <?php echo $phone; ?><br></div>
            </td>
        </tr>
        <tr>
            <td colspan="3"><center>-----------------------------------------</center></td>
        </tr>
        <tr>
            <td colspan="4">
                <div align="left">Ticket: <?php echo $numero_factura; ?></div>
                <div align="left">Cajero: <?php echo $nombre_users; ?><br></div>
                <div align="left">Fecha: <?php echo $fecha_factura . ' ' . $hora_factura; ?><br></div>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td colspan="3"><center>==============================</center></td>
        </tr>
        <tr>
            <td>Cant.</td>
            <td>Descrip.</td>
            <td>Precio Total</td>
        </tr>
        <tr>
            <td colspan="3"><center>==============================</center></td>
        </tr>
        <?php
$nums          = 1;
$sumador_total = 0;
$iva_cinco_porciento = 0;
$iva_diez_porciento  = 0;
$sql           = mysqli_query($conexion, "SELECT * FROM productos p, detalle_fact_ventas d_f_v WHERE p.id_producto = d_f_v.id_producto AND d_f_v.id_factura = '$id_factura'");

while ($row = mysqli_fetch_array($sql)) {
    $id_producto     = $row["id_producto"];
    $codigo_producto = $row['codigo_producto'];
    $cantidad        = $row['cantidad'];
    $desc_tmp        = $row['desc_venta'];
    $nombre_producto = $row['nombre_producto'];
    $iva_producto    = $row['iva_producto'];
    $precio_venta   = $row['precio_venta'];

    $precio_venta_f = formatear_moneda($simbolo_moneda, $precio_venta); //Formateo variables
     /** Casting de variables */
    $cantidad       = (int)$cantidad;
    $precio_venta_f = (float)$precio_venta_f;

    $precio_total   = $precio_venta_f * $cantidad;
    $final_items    = rebajas($precio_total, $desc_tmp); //Aplicando el descuento
    
    if ($iva_producto == 5) {
        $iva_cinco_porciento += ($precio_total / 21);
    } else if ($iva_producto == 10) {
        $iva_diez_porciento  += ($precio_total / 11);
    }

    /*--------------------------------------------------------------------------------*/
    $precio_total_f = formatear_moneda($simbolo_moneda, $final_items); //Precio total formateado

    $sumador_total += $precio_total_f; //Sumador
    if ($nums % 2 == 0) {
        $clase = "clouds";
    } else {
        $clase = "silver";
    }
    ?>

    <tr>
        <td><?php echo $cantidad; ?></td>
        <td><?php echo $nombre_producto; ?></td>
        <td><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $precio_total); ?></td>
    </tr>
    <?php

    $nums++;
}
$total_factura = formatear_moneda($simbolo_moneda, $sumador_total);
$cambio        = $recibido - $total_factura;
?>
<tr>
<td colspan="3"><center>----------------------------------------</center></td>
</tr>
<tr>
<td colspan="2">TOTAL:</td>
<td><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $total_factura); ?></td>
</tr>
<tr>
<td colspan="2"> PAGO:</td>
<td><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $recibido); ?></td>
</tr>
<tr>
<td colspan="2"> VUELTO:</td>
<td><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $cambio); ?></td>
</tr>
<?php
    if ($iva_cinco_porciento != 0 && $iva_diez_porciento == 0){
?>
    <td colspan="2">IVA 5%</td>
    <td><?=$simbolo_moneda . ' ' .formatear_moneda($simbolo_moneda, $iva_cinco_porciento)?></td>
<?php
    } else if ($iva_diez_porciento != 0 && $iva_cinco_porciento == 0) {
?>
    <td colspan="2">IVA 10%</td>
    <td><?=$simbolo_moneda . ' ' .formatear_moneda($simbolo_moneda, $iva_diez_porciento)?></td>
<?php
    } else if ($iva_cinco_porciento != 0 && $iva_diez_porciento != 0) {
?>
    <td>IVA 5%</td>
    <td><?=$simbolo_moneda . ' ' .formatear_moneda($simbolo_moneda, $iva_cinco_porciento)?></td>
    <td>IVA 10%</td>
    <td><?=$simbolo_moneda . ' ' .formatear_moneda($simbolo_moneda, $iva_diez_porciento)?></td>
<?php
    }
?>
<tr>
<td colspan="3"><center>----------------------------------------</center></td>
</tr>
<tr>
<td colspan="2">NO. DE ARTICULOS:</td>
<td align="center"> 1 </td>
</tr>
<tr>
<td colspan="3"><center>----------------------------------------</center></td>
</tr>
<tr>
<td colspan="3"><center>*GRACIAS POR SU COMPRA*</center></td>
</tr>
<tr>
<td colspan="3"><center></center></td>
</tr><br>
</table>



</page>
