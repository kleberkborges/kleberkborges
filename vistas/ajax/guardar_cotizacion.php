<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['id_cliente'])) {
    $errors[] = "ID VACIO";
} else if (!empty($_POST['id_cliente'])) {
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    //Archivo de funciones PHP
    require_once "../funciones.php";
    $session_id     = session_id();
    $simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
//Comprobamos si hay archivos en la tabla temporal
    $sql_count = mysqli_query($conexion, "select * from tmp_cotizacion where session_id='" . $session_id . "'");
    $count     = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>
        swal({
          title: 'No hay Productos agregados en la factura',
          text: 'Intentar nuevamente',
          type: 'error',
          confirmButtonText: 'ok'
      })</script>";
        exit;
    }
    // escaping, additionally removing everything that could be (html/javascript-) code
    $id_cliente     = intval($_POST['id_cliente']);
    $id_vendedor    = intval($_SESSION['id_users']);
    $users          = intval($_SESSION['id_users']);
    $condiciones    = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['condiciones'], ENT_QUOTES)));
    $numero_factura = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["factura"], ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["descripcion"], ENT_QUOTES)));
    $validez        = floatval($_POST['validez']);
    $date_added     = date("Y-m-d H:i:s");
    //Operacion de Creditos
    if ($condiciones == 4) {
        $estado = 2;
    } else {
        $estado = 1;
    }
//Seleccionamos el ultimo compo numero_fatura y aumentamos una
    // $sql        = mysqli_query($conexion, "SELECT LAST_INSERT_ID(id_factura) AS last FROM facturas_cot ORDER BY id_factura DESC LIMIT 0,1 ");
    // $rw         = mysqli_fetch_array($sql);
    // $id_factura = $rw['last'] + 1;
// finde la ultima fatura
    //Control de la  numero_fatura y aumentamos una
    $query_id = mysqli_query($conexion, "SELECT RIGHT(numero_factura, 6) AS  factura FROM facturas_cot ORDER BY factura DESC LIMIT 1")
    or die('error ' . mysqli_error($conexion));
    $count = mysqli_num_rows($query_id);

    if ($count != 0) {

        $data_id = mysqli_fetch_assoc($query_id);
        $factura = $data_id['factura'] + 1;
    } else {
        $factura = 1;
    }

    $buat_id = str_pad($factura, 6, "0", STR_PAD_LEFT);
    $factura = "COT-$buat_id";
// fin de numero de fatura
    // consulta principal
    $nums          = 1;
    $impuesto      = get_row('perfil', 'impuesto', 'id_perfil', 1);
    $sumador_total = 0;
    $sum_total     = 0;
    $t_iva         = 0;
    $sql           = mysqli_query($conexion, "SELECT * FROM productos, tmp_cotizacion WHERE productos.id_producto=tmp_cotizacion.id_producto AND tmp_cotizacion.session_id='" . $session_id . "'");
    while ($row = mysqli_fetch_array($sql)) {
        $id_tmp          = $row["id_tmp"];
        $id_producto     = $row['id_producto'];
        $codigo_producto = $row['codigo_producto'];
        $cantidad        = $row['cantidad_tmp'];
        $desc_tmp        = $row['desc_tmp'];
        $nombre_producto = $row['nombre_producto'];

        $precio_venta   = $row['precio_tmp'];
        $precio_venta   = str_replace('.', '', $precio_venta);
        $precio_total   = $precio_venta * $cantidad;
        $final_items    = rebajas($precio_total, $desc_tmp); //Aplicando el descuento
        /*--------------------------------------------------------------------------------*/

        $sumador_total += $precio_total; //Sumador

        //Insert en la tabla detalle_factura
        $insert_detail = mysqli_query($conexion, "INSERT INTO detalle_fact_cot VALUES (NULL, 0,'$factura','$id_producto','$cantidad','$desc_tmp','$precio_venta')");
    }
    // Fin de la consulta Principal
    $total_factura = $sumador_total;

    $insert        = mysqli_query($conexion, "INSERT INTO facturas_cot VALUES (NULL,'$factura','$date_added','$id_cliente','$id_vendedor','$condiciones','$total_factura','$estado','$users','$validez','1', '$descripcion')");

    $id_factura = mysqli_insert_id($conexion);

    $update_fact_cot = mysqli_query($conexion, "UPDATE detalle_fact_cot SET id_factura = '$id_factura' WHERE numero_factura = '$factura'");

    $delete        = mysqli_query($conexion, "DELETE FROM tmp_cotizacion WHERE session_id='" . $session_id . "'");
    // SI TODO ESTA CORRECTO

    if ($insert_detail) {
        echo "<script>
            $('#modal_cot').modal('show');
        </script>";
        $messages[] = "Presupuesto agregado correctamente.";
    } else {
        $errors[] = "Algo ha salido mal, intentalo nuevamente." . mysqli_error($conexion);
    }
} else {
    $errors[] = "Error desconocido.";
}

if (isset($errors)) {

    ?>
    <div class="alert alert-danger" role="alert">
        <strong>¡Error!</strong>
        <?php
foreach ($errors as $error) {
        echo $error;
    }
    ?>
    </div>
    <?php
}
if (isset($messages)) {

    ?>
    <div class="alert alert-success" role="alert">
        <strong>¡Éxito!</strong>
        <?php
foreach ($messages as $message) {
        echo $message;
    }
    ?>
    </div>
    <?php
}

?>
<!-- Modal -->
<div class="modal fade" id="modal_cot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='fa fa-edit'></i> INFORMACIÓN</h4>
            </div>
            <div class="modal-body" align="center">
                <strong><h3>Nº COTIZACION</h3></strong>
                <div class="alert alert-info" align="center">
                    <strong><h1>
                        <?php echo $factura; ?>
                    </h1></strong>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="imprimir2" class="btn btn-success btn-block btn-lg waves-effect waves-light" onclick="printFactura('<?php echo $factura; ?>');" accesskey="p"><span class="fa fa-print"></span>IMPRIMIR</button>
            </div>
        </div>
    </div>
</div>