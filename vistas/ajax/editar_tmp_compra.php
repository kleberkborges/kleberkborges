<?php
/*-------------------------
Autor: Delmar Lopez
Web: softwys.com
Mail: softwysop@gmail.com
---------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$id_factura     = $_SESSION['id_factura'];
$numero_factura = $_SESSION['numero_factura'];
$session_id     = session_id();
if (isset($_POST['id'])) {$id = $_POST['id'];}
if (isset($_POST['cantidad'])) {$cantidad = $_POST['cantidad'];}
/* Connect To Database*/
require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include "../funciones.php";
if (!empty($id) and !empty($cantidad)) {
    $id_producto    = get_row('productos', 'id_producto', 'codigo_producto', $id);
    $costo_producto = get_row('productos', 'costo_producto', 'id_producto', $id_producto);
    //Cmprobamos si agregamos un producto a la tabla tmp_compra
    $comprobar = mysqli_query($conexion, "select * from detalle_fact_compra where id_producto='" . $id_producto . "'");
    if ($row = mysqli_fetch_array($comprobar)) {
        $cant         = $row['cantidad'] + $cantidad;
        $update       = agregar_stock($id_producto, $cantidad); //Agrega al  inventario
        $sql          = "UPDATE detalle_fact_compra SET cantidad='" . $cant . "' WHERE id_producto='" . $id_producto . "'";
        $query_update = mysqli_query($conexion, $sql);

    } else {
        $insert_tmp = mysqli_query($conexion, "INSERT INTO detalle_fact_compra (id_factura,numero_factura, id_producto,cantidad,precio_costo) VALUES ('$id_factura','$numero_factura','$id_producto','$cantidad','$costo_producto')");
        $update     = agregar_stock($id_producto, $cantidad); // Descuenta del inventario
    }

}
if (isset($_GET['id'])) //codigo elimina un elemento del array
{
    $id_detalle = intval($_GET['id']);
    $id_detalle = intval($_GET['id']);
    $id_prod    = get_row('detalle_fact_compra', 'id_producto', 'id_detalle', $id_detalle);
    $quantity   = get_row('detalle_fact_compra', 'cantidad', 'id_detalle', $id_detalle);
    $update     = eliminar_stock($id_prod, $quantity); //Vuelve agregar al inventario
    $delete     = mysqli_query($conexion, "DELETE FROM detalle_fact_compra WHERE id_detalle='" . $id_detalle . "'");
}
$simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
?>
<div class="table-responsive">
    <table class="table table-sm">
        <thead class="thead-default">
            <tr>
                <th class='text-center'>COD.</th>
                <th class='text-center'>CANT.</th>
                <th colspan="3">DESCRIP.</th>
                <th class='text-center'>COSTO</th>
                <th class='text-right'>TOTAL</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php
$sumador_total = 0;
$iva_cinco_porciento = 0;
$iva_diez_porciento  = 0;
$exento = 0;
$sql           = mysqli_query($conexion, "SELECT * FROM productos, facturas_compras, detalle_fact_compra WHERE facturas_compras.id_factura=detalle_fact_compra.id_factura AND  facturas_compras.id_factura='$id_factura' AND productos.id_producto=detalle_fact_compra.id_producto");
while ($row = mysqli_fetch_array($sql)) {
    $id_detalle      = $row["id_detalle"];
    $codigo_producto = $row['codigo_producto'];
    $cantidad        = $row['cantidad'];
    $nombre_producto = $row['nombre_producto'];
    $iva_producto    = $row['iva_producto'];
    $unidad_medida   = $row['unidad_medida'];

    $precio_costo   = $row['precio_costo'];
    
    $precio_total   = $precio_costo * $cantidad;
    
    $sumador_total += $precio_total; //Sumador

    if ($iva_producto == 5) {
        $iva_cinco_porciento += ($precio_total / 21);
    } else if ($iva_producto == 10) {
        $iva_diez_porciento += ($precio_total / 11);
    } else if ($iva_producto == 0) {
        $exento += $precio_total;
    }

    ?>
    <tr>
        <td class='text-center'><?php echo $codigo_producto; ?></td>
        <td class='text-center'><?php echo $cantidad . ' ' . $unidad_medida; ?></td>
        <td colspan="3"><?php echo $nombre_producto; ?></td>
        <td align="right" width="15%">
            <input type="text" class="form-control txt_costo" value="<?php echo formatear_moneda($simbolo_moneda, $precio_costo); ?>" id="<?php echo $id_detalle; ?>">
        </td>
        <td class='text-right'><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $precio_total); ?></td>
        <td class='text-center'>
            <a href="#" class='btn btn-danger btn-sm waves-effect waves-light' onclick="eliminar('<?php echo $id_detalle ?>')"><i class="fa fa-remove"></i>
            </a>
        </td>
    </tr>
    <?php
}
$nom_impuesto  = get_row('perfil', 'nom_impuesto', 'id_perfil', 1);
$total_factura = $sumador_total;
$update        = mysqli_query($conexion, "UPDATE facturas_compras SET monto_factura='$total_factura' WHERE id_factura='$id_factura'");

?>
<tr style="background: #ECEEEF; font-size:15px">
    <td class="text-right"><b><?php echo $nom_impuesto . ' 10% '?></b></td>
    <td class="text-left" colspan="2"><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $iva_diez_porciento)?></td>

    <td class='text-right'><b><?php echo $nom_impuesto . ' 5% ' ?></b></td>
    <td class='text-left'><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $iva_cinco_porciento); ?>
    </td>
    <td class='text-left'><b>EXENTO</b></td>
    <td class='text-right'><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $exento); ?></td>
    <td></td>
</tr>
<?php
    $cotizaciones = obtener_cotizaciones();
    foreach ($cotizaciones as $cotizacion) {
?>
    <tr style="background: #ECEEEF">
        <td colspan="5" style="background: #FFF"></td>
        <td style="font-size: 15px;" class='text-left'><b>TOTAL <?php echo $cotizacion['symbol'] ?></b></td>

        <td style="font-size: 18px;" class='text-right'><span class="label label-danger"><b><?php echo formatear_moneda($cotizacion['symbol'], convertir_moneda($simbolo_moneda, $cotizacion['symbol'], $total_factura, $cotizacion['cotizacion'])); ?></b></span></td>

        <td></td>
    </tr>
<?php
    }
?>
</tbody>
</table>
</div>
<script>
    $(document).ready(function () {
        $('.txt_costo').off('blur');
        $('.txt_costo').on('blur',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
        // if(keycode == '13'){
            id_tmp = $(this).attr("id");
            costo = $(this).val();
             //Inicia validacion
            var regex = new RegExp('^[0-9. ]{2,}$', 'g');

            if (!(regex.test(costo))) {
                $.Notification.notify('error','bottom center','ERROR!', 'EL COSTO DIGITADO NO ES UN FORMATO VALIDO')
                $(this).focus();
                return false;
            }
    //Fin validacion
    $.ajax({
        type: "POST",
        url: "../ajax/editar_costo.php",
        data: "id_tmp=" + id_tmp + "&costo=" + costo,
        success: function(datos) {
         $("#resultados").load("../ajax/editar_tmp_compra.php");
         $.Notification.notify('success','bottom center','EXITO!', 'COSTO ACTUALIZADO CORRECTAMENTE')
     }
 });
        // }
    });
    });
</script>

