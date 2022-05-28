<?php
/*-------------------------
Autor: Delmar Lopez
Web: www.softwys.com
Mail: softwysop@gmail.com
---------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id = session_id();
$id_factura = $_SESSION['id_factura'];
if (isset($_POST['id'])) {$id = $_POST['id'];}
if (isset($_POST['cantidad'])) {$cantidad = $_POST['cantidad'];}

/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Archivo de funciones PHP
require_once "../funciones.php";

if (!empty($id) and !empty($cantidad)) {
    $id_producto    = get_row('productos', 'id_producto', 'codigo_producto', $id);
    $numero_factura = get_row('facturas_cot', 'numero_factura', 'id_factura', $id_factura);
    $precio_venta   = get_row('productos', 'valor1_producto', 'id_producto', $id_producto);

    // consulta para comparar el stock con la cantidad resibida
    $query = mysqli_query($conexion, "select stock_producto, inv_producto from productos where id_producto = '$id_producto'");
    $rw    = mysqli_fetch_array($query);
    $stock = $rw['stock_producto'];
    $inv   = $rw['inv_producto'];

    //Comprobamos si ya agregamos un producto a la tabla tmp_compra
    $comprobar = mysqli_query($conexion, "select * from detalle_fact_cot where id_producto='" . $id_producto . "' and id_factura='" . $id_factura . "'");

    if ($row = mysqli_fetch_array($comprobar)) {
        $cant = $row['cantidad'] + $cantidad;
        // condicion si el stock e menor que la cantidad requerida
        if ($cant > $stock and $inv == 0) {
            echo "<script>swal('LA CANTIDAD SUPERA AL STOCK!', 'INTENTAR NUEVAMENTE', 'error')
                                $('#resultados').load('../ajax/editar_tmp_cot.php');
            </script>";
        } else {

            $sql          = "UPDATE detalle_fact_cot SET cantidad='" . $cant . "' WHERE id_producto='" . $id_producto . "' and id_factura='" . $id_factura . "'";
            $query_update = mysqli_query($conexion, $sql);
            echo "<script> $.Notification.notify('warning','bottom center','NOTIFICACIÓN', 'PRODUCTO AGREGADO A LA DATA')</script>";
        }
        // fin codicion cantaidad

    } else {
        // condicion si el stock e menor que la cantidad requerida
        if ($cantidad > $stock and $inv == 0) {
            echo "<script>swal('LA CANTIDAD SUPERA AL STOCK!', 'INTENTAR NUEVAMENTE', 'error')
                    $('#resultados').load('../ajax/editar_tmp_cot.php');
            </script>";
        } else {

            $insert_tmp = mysqli_query($conexion, "INSERT INTO detalle_fact_cot (id_factura,numero_factura, id_producto,cantidad,precio_venta) VALUES ('$id_factura','$numero_factura','$id_producto','$cantidad','$precio_venta')");
            echo "<script> $.Notification.notify('warning','bottom center','NOTIFICACIÓN', 'PRODUCTO AGREGADO A LA DATA')</script>";
        }
        // fin codicion cantaidad
    }

}
if (isset($_GET['id'])) //codigo elimina un elemento del array
{
    $id_detalle = intval($_GET['id']);
    $id_prod    = get_row('detalle_fact_cot', 'id_producto', 'id_detalle', $id_detalle);
    $quantity   = get_row('detalle_fact_cot', 'cantidad', 'id_detalle', $id_detalle);
    $delete     = mysqli_query($conexion, "DELETE FROM detalle_fact_cot WHERE id_detalle='" . $id_detalle . "'");
}
$simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
?>
<div class="table-responsive">
    <table class="table table-sm">
        <thead class="thead-default">
            <tr>
                <th class='text-center'>COD</th>
                <th class='text-center'>CANT.</th>
                <th class='text-center' colspan="3">DESCRIP.</th>
                <th class='text-center'>PRECIO <?php echo $simbolo_moneda; ?></th>
                <th class='text-center'>DESC %</th>
                <th class='text-right'>TOTAL</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
<?php
$nom_impuesto        = get_row('perfil', 'nom_impuesto', 'id_perfil', 1);
$subtotal            = 0;
$sumador_total       = 0;
$iva_cinco_porciento = 0;
$iva_diez_porciento  = 0;
$exento              = 0;
$sql            = mysqli_query($conexion, "SELECT * FROM productos, facturas_cot, detalle_fact_cot WHERE facturas_cot.id_factura=detalle_fact_cot.id_factura AND  facturas_cot.id_factura='$id_factura' AND productos.id_producto=detalle_fact_cot.id_producto");
while ($row = mysqli_fetch_array($sql)) {
    $id_detalle      = $row["id_detalle"];
    $id_producto     = $row["id_producto"];
    $codigo_producto = $row['codigo_producto'];
    $cantidad        = $row['cantidad'];
    $desc_tmp        = $row['desc_venta'];
    $nombre_producto = $row['nombre_producto'];
    $precio_venta    = $row['precio_venta'];
    $iva_producto    = $row['iva_producto'];
    
    $precio_total    = $precio_venta * $cantidad;
    $final_items     = rebajas($precio_total, $desc_tmp); //Aplicando el descuento
    /*--------------------------------------------------------------------------------*/
    $sumador_total += $final_items; //Sumador
    $subtotal = $sumador_total;

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
        <td class='text-center'><?php echo $cantidad; ?></td>
        <td colspan="3"><?php echo $nombre_producto; ?></td>
        <td class='text-center'>
            <div class="input-group">
                <select id="<?php echo $id_detalle; ?>" class="form-control employee_id">
<?php
    $sql1 = mysqli_query($conexion, "SELECT * FROM productos WHERE id_producto = '$id_producto'");
    while ($rw1 = mysqli_fetch_array($sql1)) {
?>
                        <option selected disabled value="<?php echo $precio_venta ?>"><?php echo formatear_moneda($simbolo_moneda, $precio_venta); ?></option>
                        <option value="<?php echo $rw1['valor1_producto'] ?>">PV <?php echo formatear_moneda($simbolo_moneda, $rw1['valor1_producto']); ?></option>
                        <option value="<?php echo $rw1['valor2_producto'] ?>">PM <?php echo formatear_moneda($simbolo_moneda, $rw1['valor2_producto']); ?></option>
<?php
    }
?>
                </select>
            </div>
        </td>
        <td align="right" width="15%">
            <input type="text" class="form-control txt_desc" style="text-align:center" value="<?php echo $desc_tmp; ?>" id="<?php echo $id_detalle; ?>">
        </td>
        <td class='text-right'><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $final_items); ?></td>
        <td class='text-center'>
            <a href="#" class='btn btn-danger btn-sm waves-effect waves-light' onclick="eliminar('<?php echo $id_detalle ?>')"><i class="fa fa-remove"></i>
            </a>
        </td>
    </tr>
<?php
}
$total_factura = $subtotal;

$update        = mysqli_query($conexion, "UPDATE facturas_cot SET monto_factura='$total_factura' WHERE id_factura='$id_factura'");

?>
<tr style="background: #ECEEEF; font-size:15px">
    <td class="text-right"><b><?php echo $nom_impuesto . ' 10% '?></b></td>
    <td class="text-left" colspan="3"><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $iva_diez_porciento)?></td>

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
        <td colspan="6" style="background: #FFF"></td>
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
        $('.txt_desc').off('blur');
        $('.txt_desc').on('blur',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
        // if(keycode == '13'){
            id_detalle = $(this).attr("id");
            desc = $(this).val();
             //Inicia validacion
             if (isNaN(desc)) {
                $.Notification.notify('error','bottom center','ERROR', 'DIGITAR UN DESCUENTO VALIDO')
                $(this).focus();
                return false;
            }
    //Fin validacion
    $.ajax({
        type: "POST",
        url: "../ajax/editar_desc_cot2.php",
        data: "id_detalle=" + id_detalle + "&desc=" + desc,
        success: function(datos) {
         $("#resultados").load("../ajax/editar_tmp_cot.php");
         $.Notification.notify('success','bottom center','EXITO!', 'DESCUENTO ACTUALIZADO CORRECTAMENTE')
     }
 });
        // }
    });

          $(".employee_id").on("change", function(event) {
         id_detalle = $(this).attr("id");
        precio = $(this).val();
        $.ajax({
            type: "POST",
            url: "../ajax/editar_precio_cot2.php",
            data: "id_detalle=" + id_detalle + "&precio=" + precio,
            success: function(datos) {
               $("#resultados").load("../ajax/editar_tmp_cot.php");
               $.Notification.notify('success','bottom center','EXITO!', 'PRECIO ACTUALIZADO CORRECTAMENTE')
           }
       });
    });

    });
</script>

