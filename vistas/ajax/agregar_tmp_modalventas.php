<?php
/*-------------------------
Autor: Delmar Lopez
Web: www.softwys.com
Mail: softwysop@gmail.com
---------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id = session_id();
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Archivo de funciones PHP
require_once "../funciones.php";

if (isset($_POST['id'])) {$id = $_POST['id'];}
if (isset($_POST['cantidad'])) {$cantidad = $_POST['cantidad'];}
if (isset($_POST['precio_venta'])) {$precio_venta = str_replace('.', '', $_POST['precio_venta']);}
if (!empty($id) and !empty($cantidad) and !empty($precio_venta)) {
    // consulta para comparar el stock con la cantidad recibida
    $query = mysqli_query($conexion, "SELECT stock_producto, inv_producto FROM productos WHERE id_producto = '$id'");
    $rw    = mysqli_fetch_array($query);
    $stock = $rw['stock_producto'];
    $inv   = $rw['inv_producto'];
    
    //Comprobamos si agregamos un producto a la tabla tmp_compra
    $comprobar = mysqli_query($conexion, "SELECT * FROM tmp_ventas, productos WHERE productos.id_producto = tmp_ventas.id_producto AND tmp_ventas.id_producto='$id' AND tmp_ventas.session_id='$session_id'");
    if ($row = mysqli_fetch_array($comprobar)) {
        $cant = $row['cantidad_tmp'] + $cantidad;
        // condicion si el stock e menor que la cantidad requerida
        if ($cant > $row['stock_producto'] and $inv == 0) {
            echo "<script>swal('LA CATIDAD SUPERA AL STOCK', 'INTENTAR NUEVAMENTE', 'error')
            $('#resultados').load('../ajax/agregar_tmp.php');
            </script>";
            exit;
        } else {
            $sql          = "UPDATE tmp_ventas SET cantidad_tmp='" . $cant . "', precio_tmp='" . $precio_venta . "' WHERE id_producto='" . $id . "' and session_id='" . $session_id . "'";
            $query_update = mysqli_query($conexion, $sql);
            echo "<script> $.Notification.notify('success','bottom center','NOTIFICACIÓN', 'PRODUCTO AGREGADO A LA FACTURA CORRECTAMENTE')</script>";
        }
        // fin codicion cantaidad
        
    } else {
        // condicion si el stock es menor que la cantidad requerida
        if ($cantidad > $stock and $inv == 0) {
            echo "<script>swal('LA CATIDAD SUPERA AL STOCK', 'INTENTAR NUEVAMENTE', 'error')
            $('#resultados').load('../ajax/agregar_tmp.php');
            </script>";
            exit;
        } else {
            $insert_tmp = mysqli_query($conexion, "INSERT INTO tmp_ventas (id_producto,cantidad_tmp,precio_tmp,desc_tmp,session_id) VALUES ('$id','$cantidad','$precio_venta','0','$session_id')");
            echo "<script> $.Notification.notify('success','bottom center','NOTIFICACIÓN', 'PRODUCTO AGREGADO A LA FACTURA CORRECTAMENTE')</script>";
        }
        // fin codicion cantaidad
    }

}
if (isset($_GET['id'])) //codigo elimina un elemento del array
{
    $id_tmp = intval($_GET['id']);
    $delete = mysqli_query($conexion, "DELETE FROM tmp_ventas WHERE id_tmp='" . $id_tmp . "'");
}

$simbolo_moneda = obtener_moneda();

?>
<div class="table-responsive">
    <table class="table table-sm">
        <thead class="thead-default">
            <tr>
                <th class='text-center'>COD</th>
                <th class='text-center'>CANT.</th>
                <th class='text-center'>DESCRIP.</th>
                <th class='text-center' colspan="2">PRECIO <?php echo $simbolo_moneda; ?></th>
                <th class='text-center'>DESC %</th>
                <th class='text-right'>TOTAL</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
$nom_impuesto   = get_row('perfil', 'nom_impuesto', 'id_perfil', 1);
$exento    = 0;
$iva_cinco_porciento = 0;
$iva_diez_porciento = 0;
$total_impuesto = 0;
$total_factura  = 0;
$sql            = mysqli_query($conexion, "SELECT * FROM productos, tmp_ventas WHERE productos.id_producto=tmp_ventas.id_producto AND tmp_ventas.session_id='" . $session_id . "'");
while ($row = mysqli_fetch_array($sql)) {
    $id_tmp          = $row["id_tmp"];
    $codigo_producto = $row['codigo_producto'];
    $id_producto     = $row['id_producto'];
    $cantidad        = $row['cantidad_tmp'];
    $desc_tmp        = $row['desc_tmp'];
    $nombre_producto = $row['nombre_producto'];
    $iva_producto    = $row['iva_producto'];

    $unidad_medida   = $row['unidad_medida'];

    $precio_venta   = $row['precio_tmp'];
    $precio_total   = $precio_venta * $cantidad;
    $final_items    = rebajas($precio_total, $desc_tmp); //Aplicando el descuento
    /*--------------------------------------------------------------------------------*/

    if ($iva_producto == 5){
        $iva_cinco_porciento += ($precio_total / 21);
    } else if ($iva_producto == 10) {
        $iva_diez_porciento += ($precio_total / 11);
    } else if ($iva_producto == 0) {
        $exento += $precio_total;
    }

    $precio_total_rebajado = rebajas($precio_total, $desc_tmp);
    
    $total_factura += $precio_total_rebajado; //Sumador

?>
    <tr>
        <td class='text-center'><?php echo $codigo_producto; ?></td>
        <td class='text-center'><?php echo $cantidad . ' ' . $unidad_medida; ?></td>
        <td><?php echo $nombre_producto; ?></td>
        <td class='text-center' colspan="2">
            <div class="input-group">
                <select id="<?php echo $id_tmp; ?>" class="form-control employee_id">
<?php
    $sql1 = mysqli_query($conexion, "SELECT * FROM productos WHERE id_producto='$id_producto'");
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
                <input type="text" class="form-control txt_desc" style="text-align:center" value="<?php echo $desc_tmp; ?>" id="<?php echo $id_tmp; ?>">
        </td>
        <td class='text-right'><?php echo $simbolo_moneda . ' ' . formatear_moneda($simbolo_moneda, $precio_total); ?></td>
        <td class='text-center'>
            <a href="#" class='btn btn-danger btn-sm waves-effect waves-light' onclick="eliminar('<?php echo $id_tmp ?>')"><i class="fa fa-remove"></i>
            </a>
        </td>
    </tr>
    <?php
}
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
        $('.txt_desc').off('blur');
        $('.txt_desc').on('blur',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
        // if(keycode == '13'){
            id_tmp = $(this).attr("id");
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
        url: "../ajax/editar_desc_venta.php",
        data: "id_tmp=" + id_tmp + "&desc=" + desc,
        success: function(datos) {
           $("#resultados").load("../ajax/agregar_tmp.php");
           $.Notification.notify('success','bottom center','EXITO!', 'DESCUENTO ACTUALIZADO CORRECTAMENTE')
       }
   });
        // }
    });
     $(".employee_id").on("change", function(event) {
         id_tmp = $(this).attr("id");
        precio = $(this).val();
        $.ajax({
            type: "POST",
            url: "../ajax/editar_precio_venta.php",
            data: "id_tmp=" + id_tmp + "&precio=" + precio,
            success: function(datos) {
               $("#resultados").load("../ajax/agregar_tmp.php");
               $.Notification.notify('success','bottom center','EXITO!', 'PRECIO ACTUALIZADO CORRECTAMENTE')
           }
       });
    });

    });
</script>