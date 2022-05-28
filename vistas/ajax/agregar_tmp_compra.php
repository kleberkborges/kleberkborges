<?php
/*-------------------------
Autor: Delmar Lopez
Web: wwww.softwys.com
Mail: softwysop@gmail.com
---------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id = session_id();
if (isset($_POST['id'])) {$id = $_POST['id'];}
if (isset($_POST['cantidad'])) {$cantidad = $_POST['cantidad'];}

/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Archivo de funciones PHP
require_once "../funciones.php";

if (!empty($id) and !empty($cantidad)) {
    $id_producto    = get_row('productos', 'id_producto', 'codigo_producto', $id);
    $costo_producto = get_row('productos', 'costo_producto', 'id_producto', $id_producto);
    // $costo_producto = str_replace('.', '', $costo_producto);
    // consulta para comparar si existe el producto
    $query = mysqli_query($conexion, "SELECT codigo_producto FROM productos WHERE codigo_producto = '$id'");
    $rw    = mysqli_fetch_array($query);
    
    //Cmprobamos si agregamos un producto a la tabla tmp_compra
    $comprobar = mysqli_query($conexion, "SELECT * FROM tmp_compra WHERE id_producto = '$id_producto' AND session_id = '$session_id'");
    if ($row = mysqli_fetch_array($comprobar)) {
        $cant = $row['cantidad_tmp'] + $cantidad;
        
        $sql          = "UPDATE tmp_compra SET cantidad_tmp = '$cant' WHERE id_producto = '$id_producto' AND session_id = '$session_id'";
        $query_update = mysqli_query($conexion, $sql);
        
    } else {
        
        $insert_tmp = mysqli_query($conexion, "INSERT INTO tmp_compra (id_producto,cantidad_tmp,costo_tmp,session_id) VALUES ('$id_producto','$cantidad','$costo_producto','$session_id')");
        
    }
    
}
if (isset($_GET['id'])) //codigo elimina un elemento del array
{
    $id_tmp = intval($_GET['id']);
    $delete = mysqli_query($conexion, "DELETE FROM tmp_compra WHERE id_tmp='" . $id_tmp . "'");
}
$simbolo_moneda = obtener_moneda();
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
    $nom_impuesto   = get_row('perfil', 'nom_impuesto', 'id_perfil', 1);
    $sumador_total  = 0;
    $exento         = 0;
    $iva_cinco_porciento = 0;
    $iva_diez_porciento = 0;
    $total_impuesto = 0;
    $total_factura  = 0;
    $sql            = mysqli_query($conexion, "SELECT * FROM productos p, tmp_compra tmp_c WHERE p.id_producto = tmp_c.id_producto AND tmp_c.session_id = '$session_id'");
    while ($row = mysqli_fetch_array($sql)) {
        $iva_producto    = $row['iva_producto'];
        $id_tmp          = $row["id_tmp"];
        $id_producto     = $row['id_producto'];
        $codigo_producto = $row['codigo_producto'];
        $cantidad        = $row['cantidad_tmp'];
        $nombre_producto = $row['nombre_producto'];
        $unidad_medida   = $row['unidad_medida'];

        $precio_costo   = $row['costo_tmp'];
        $precio_total   = $precio_costo * $cantidad;
        /*--------------------------------------------------------------------------------*/
        
        if ($iva_producto == 5){
            $iva_cinco_porciento += ($precio_total / 21);
        } else if ($iva_producto == 10) {
            $iva_diez_porciento += ($precio_total / 11);
        } else if ($iva_producto == 0) {
            $exento += $precio_total;
        }
        
        $sumador_total += $precio_total; //Sumador
        $total_factura = $sumador_total;

?>
    <tr>
        <td class='text-center'><?php echo $codigo_producto; ?></td>
        <td class='text-center'><?php echo $cantidad . ' ' . $unidad_medida; ?></td>
        <td colspan="3"><?php echo $nombre_producto; ?></td>
        <td align="center" width="15%">
            <input type="text" class="form-control txt_costo" value="<?php echo formatear_moneda($simbolo_moneda, $precio_costo); ?>" id="<?php echo $id_tmp; ?>">
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
<script type="text/javascript" src="../../js/formatear_moneda.js" defer></script>
<script>
    $(document).ready(function () {
        formatear_moneda(<?=$id_tmp?>);
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
        url: "../ajax/editar_costo_compra.php",
        data: "id_tmp=" + id_tmp + "&costo=" + costo,
        success: function(datos) {
         $("#resultados").load("../ajax/agregar_tmp_compra.php");
         $.Notification.notify('success','bottom center','EXITO!', 'COSTO ACTUALIZADO CORRECTAMENTE')
     }
 });
        // }
    });
    });
</script>

