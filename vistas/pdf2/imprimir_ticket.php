<?php
include '../ajax/is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
?>
<style type="text/css" media="print">
    #Imprime {
        height: auto;
        width: 377px;
        margin: 0px;
        padding: 0px;
        float: left;
        font-family: "Comic Sans MS", cursive;
        font-size: 5px;
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
require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include "../funciones.php";
$simbolo_moneda = obtener_moneda();
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
$users         = intval($_SESSION['id_users']);
/*Fin datos empresa*/
/*Datos de la ultima Factura*/
$sql          = mysqli_query($conexion, "SELECT MAX(id_factura) AS last FROM facturas_ventas WHERE id_users_factura='$users'");
$rw           = mysqli_fetch_array($sql);
$last_factura = $rw['last'];
/*Fin de la ultima factura*/
$sql_factura    = mysqli_query($conexion, "SELECT * FROM facturas_ventas, users WHERE facturas_ventas.id_vendedor=users.id_users AND facturas_ventas.id_factura='" . $last_factura . "'");
$count          = mysqli_num_rows($sql_factura);
$rw_factura     = mysqli_fetch_array($sql_factura);
$nombre_users   = $rw_factura['nombre_users'];
$fecha_factura  = date("d/m/Y", strtotime($rw_factura['fecha_factura']));
$hora_factura   = date('H:i:s', strtotime($rw_factura['fecha_factura']));
$numero_factura = $rw_factura['numero_factura'];
$recibido       = $rw_factura['dinero_resibido_fac'];
$condiciones = $rw_factura['condiciones'];

if ($condiciones == 1) {
    $forma_pago = 'Efectivo';
} else if ($condiciones == 2) {
    $forma_pago = 'Cheque';
} else if ($condiciones == 3) {
    $forma_pago = 'Tarjeta';
} else if ($condiciones == 4) {
    $forma_pago = 'Cr??dito';
}

?>

<page pageset='new' backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm' footer='page'>
    <table width="175px" style="font-size:8px; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif" border="0" >
        <tr>
            <td colspan="3">
                <div align="center" style="font-size:22px"><strong><?php echo $bussines_name; ?></strong><br></div>
                <div align="center" style="font-size:16px"><strong><?php echo $giro; ?></strong><br></div>
                <div align="center" style="font-size:16px"><strong>RUC / C??dula: <?php echo $fiscal; ?></strong><br></div>
                <div align="center" style="font-size:14px"><strong><?php echo $address; ?></strong><br></div>
                <div align="center" style="font-size:14px"><strong><?php echo $city; ?></strong><br></div>
                <div align="center" style="font-size:14px"><strong>Tel: </strong> <?php echo $phone; ?><br></div>
            </td>
        </tr>
        <tr>
            <td colspan="3"><center>-----------------------------------------</center></td>
        </tr>
        <tr>
            <td colspan="4">
                <div align="left">Ticket: <?php echo $numero_factura; ?></div>
                <div align="left">Forma de pago: <?=$forma_pago?></div>
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
$numero_articulos = 0;
$sql           = mysqli_query($conexion, "SELECT * FROM productos p, detalle_fact_ventas d_f_v, facturas_ventas f_v WHERE p.id_producto = d_f_v.id_producto AND d_f_v.id_factura = '$last_factura' AND f_v.id_factura = '$last_factura'");

while ($row = mysqli_fetch_array($sql)) {
    $id_producto     = $row["id_producto"];
    $codigo_producto = $row['codigo_producto'];
    $cantidad        = $row['cantidad'];
    $desc_tmp        = $row['desc_venta'];
    $nombre_producto = $row['nombre_producto'];
    $iva_producto    = $row['iva_producto'];
    $precio_venta   = $row['precio_venta'];

     /** Casting de variables */
    $cantidad     = (int)$cantidad;
    $precio_venta = (float)$precio_venta;

    $precio_total   = $precio_venta * $cantidad;
    $final_items    = rebajas($precio_total, $desc_tmp); //Aplicando el descuento
    
    if ($iva_producto == 5) {
        $iva_cinco_porciento += ($precio_total / 21);
    } else if ($iva_producto == 10) {
        $iva_diez_porciento  += ($precio_total / 11);
    }

    $numero_articulos += $cantidad;

    /*--------------------------------------------------------------------------------*/
    $precio_total = $final_items; //Precio total formateado

    $sumador_total += $precio_total; //Sumador
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
$total_factura = $sumador_total;
$cambio        = $recibido - $total_factura;

/** OBTENER OBSERVACION / DESCRIPCION DE LA FACTURA */

$obtener_observacion = mysqli_query($conexion, "SELECT descripcion FROM facturas_ventas WHERE id_factura = '$last_factura'");
$descripcion = mysqli_fetch_row($obtener_observacion);

?>
<tr>
<td colspan="3"><center>----------------------------------------</center></td>
</tr>
<?php
    if ($iva_cinco_porciento || $iva_diez_porciento) {
?>
    <tr style="font-size: 6px">
        <td colspan="2">IVA 5%: <?=formatear_moneda($simbolo_moneda, $iva_cinco_porciento)?></td>
     <td>IVA 10%: <?=formatear_moneda($simbolo_moneda, $iva_diez_porciento)?></td>
    </tr>
<?php
    }
?>
<tr>
<td colspan="2">TOTAL:</td>
<td><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $total_factura); ?></td>
</tr>

<?php 
    if ($condiciones == 4){
?>
<tr>
    <td colspan="2">ANTICIPO:</td>
    <td><?=$simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $recibido)?></td>
</tr>
<?php
    }
?>
<tr>
<td colspan="3">Obs.: <?=$descripcion[0]?></td>
</tr>
<tr>
<td colspan="3"><center>----------------------------------------</center></td>
</tr>
<tr>
<td colspan="2">NO. DE ARTICULOS:</td>
<td align="center"> <?=$numero_articulos?> </td>
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
