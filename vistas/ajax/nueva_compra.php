<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['id_proveedor'])) {
    $errors[] = "ID VACIO";
} else if (!empty($_POST['id_proveedor'])) {
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    //Archivo de funciones PHP
    require_once "../funciones.php";
    $session_id = session_id();
    //Comprobamos si hay archivos en la tabla temporal
    $sql_count = mysqli_query($conexion, "select * from tmp_compra where session_id='" . $session_id . "'");
    $count     = mysqli_num_rows($sql_count);
    if ($count == 0) {
        echo "<script>
            swal('NO HAY PRODUCTOS AGREGADOS EN LA FACTURA', 'INTENTAR DE NUEVO', 'error')
        </script>";
        exit;
    }

    // escaping, additionally removing everything that could be (html/javascript-) code
    $id_proveedor = intval($_POST['id_proveedor']);
    $id_vendedor  = intval($_SESSION['id_users']);
    $users        = intval($_SESSION['id_users']);
    $condiciones  = intval($_POST['condiciones']);
    $factura      = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["factura"], ENT_QUOTES)));
    $referencia   = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["ref"], ENT_QUOTES)));
    $fecha        = $_POST["fecha"];
    //Operacion de Creditos
    if ($condiciones == 4) {
        $cantidad_pagos = intval($_POST['cantidad_pagos']);
        $estado = 2;
    } else {
        $estado = 1;
    }
    // check if numero_factura already exists
    $sql                   = "SELECT * FROM facturas_compras WHERE numero_factura ='$factura';";
    $query_check_user_name = mysqli_query($conexion, $sql);
    $query_check_factura   = mysqli_num_rows($query_check_user_name);
    if ($query_check_factura == true) {
        echo "<script>
            swal('NUMERO DE FACTURA YA ESTA REGISTRADO', 'Inténtalo de nuevo!', 'error')
        </script>";
        exit;
    }
    //Operacion de Creditos
    if ($condiciones == 4) {
        $estado = 2;
    } else {
        $estado = 1;
    }

    /** OBTENER DATOS AGREGADOS EN LA TABLA tmp_compra */
    $simbolo_moneda = obtener_moneda();

    $nums = 1;
    $sumador_total = 0;
    $subtotal  = 0;
    $subtotal_iva = 0;
    $total_factura = 0;
    $query = mysqli_query($conexion, "SELECT * FROM productos, tmp_compra WHERE productos.id_producto = tmp_compra.id_producto AND tmp_compra.session_id='$session_id'");
    while ($row = mysqli_fetch_array($query)) {
        $id_tmp          = $row["id_tmp"];
        $id_producto     = $row["id_producto"];
        $codigo_producto = $row['codigo_producto'];
        $cantidad        = $row['cantidad_tmp'];
        $nombre_producto = $row['nombre_producto'];
        $precio_venta = $row['costo_tmp'];

        $precio_venta = (float)$precio_venta;
        $costo_total   = $precio_venta * $cantidad;
        $sumador_total += $costo_total;

        /** INSERT EN LA TABLA detalle_fact_compras
         * Insertamos con id_factura = 0
         */
        $insert_detail = mysqli_query($conexion, "INSERT INTO detalle_fact_compra VALUES (NULL, 0,'$factura','$id_producto','$cantidad','$precio_venta')");

        //GUARDAR LAS ENTRADAS EN CONTROL DE INVENTARIO
        $saldo_total = $cantidad * $precio_venta;
        $sql_kardex  = mysqli_query($conexion, "SELECT * FROM kardex WHERE producto_kardex='$id_producto' ORDER BY id_kardex DESC LIMIT 1");
        $rww         = mysqli_fetch_array($sql_kardex);
        $cant_saldo = $rww['cant_saldo'] + $cantidad;
        $saldo_full     = ($rww['total_saldo'] + $saldo_total);
        $costo_promedio = ($rww['total_saldo'] + $saldo_total) / $cant_saldo;
        $tipo           = 1;

        guardar_entradas($fecha, $id_producto, $cantidad, $precio_venta, $saldo_total, $cant_saldo, $costo_promedio, $saldo_full, $fecha, $users, $tipo);
        //ACTUALIZA EN EL STOCK
        $sql2    = mysqli_query($conexion, "SELECT * FROM productos WHERE id_producto = '$id_producto'");
        $rw      = mysqli_fetch_array($sql2);
        $old_qty = $rw['stock_producto']; //Cantidad encontrada en el inventario
        $new_qty = $old_qty + $cantidad; //Nueva cantidad en el inventario
        $update  = mysqli_query($conexion, "UPDATE productos SET stock_producto = '$new_qty' WHERE id_producto = '$id_producto'"); //Actualizo la nueva cantidad en el inventario
        $update  = mysqli_query($conexion, "UPDATE productos SET costo_producto='$precio_venta', valor1_producto = '$precio_venta' WHERE id_producto = '$id_producto'"); //Actualizo el nuevo costo de producto

        $nums++;
    }
    /** INSERT EN LA TABLA facturas_compras */
    $total_factura = $sumador_total;
    
    if ($condiciones == 4) {
        $recibido = str_replace('.', '', $_POST['resibido']);

        $saldo_credito = $sumador_total - $recibido;
        $recibido_formato = formatear_moneda($simbolo_moneda, $recibido);
        $insert_prima = mysqli_query($conexion, "INSERT INTO credito_proveedor VALUES (NULL,'$factura','$fecha','$id_proveedor','$total_factura','$saldo_credito','1','$users','1', '$cantidad_pagos', 0)");
        $last_credito = mysqli_insert_id($conexion);
        $insert_abono = mysqli_query($conexion, "INSERT INTO creditos_abonos_prov VALUES (NULL,'$factura','$fecha','$id_proveedor','$total_factura','$recibido','$saldo_credito','$users','1','CREDITO INICIAL', '$last_credito')");
    }
    $insert = mysqli_query($conexion, "INSERT INTO facturas_compras VALUES (NULL,'$factura','$fecha','$id_proveedor','$id_vendedor','$condiciones','$total_factura','$estado','$users','1','$referencia', NULL, NULL)");
    
    $last_inserted_id = mysqli_insert_id($conexion);
    
    $delete = mysqli_query($conexion, "DELETE FROM tmp_compra WHERE session_id = '$session_id'");
    
    if ($condiciones == 2) {
        $numero_cheque = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["numero_cheque"], ENT_QUOTES)));
        mysqli_query($conexion, "UPDATE facturas_compras SET numero_cheque = '$numero_cheque' WHERE numero_factura = '$factura'");
    } else if ($condiciones == 3) {
        $numero_comprobante = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST["numero_comprobante"], ENT_QUOTES)));
        mysqli_query($conexion, "UPDATE facturas_compras SET numero_comprobante = '$numero_comprobante' WHERE numero_factura = '$factura'");
    }


    /** UPDATE EN LA TABLA detalle_fact_compras */
    mysqli_query($conexion, "UPDATE detalle_fact_compra SET id_factura = '$last_inserted_id' WHERE numero_factura = '$factura'");
    if($condiciones == 4) {
        mysqli_query($conexion, "UPDATE credito_proveedor SET id_factura = '$last_inserted_id' WHERE numero_factura = '$factura'");
    }
    // SI TODO ESTA CORRECTO
    if ($condiciones == 4) {
        echo "<script>
            swal('COMPRA AL CRÉDITO GUARDADA CON ATICIPO DE: $simbolo_moneda $recibido_formato', 'Factura: $factura', 'success')
        </script>";
        exit;
    }
    if (mysqli_error($conexion)) {
        $errors[] = "Algo ha salido mal, intentelo nuevamente." . mysqli_error($conexion);
    } else if ($insert_detail) {
        $messages[] = "Compra agregada correctamente.";
    } else {
        $errors[] = "Algo ha salido mal, intentalo nuevamente." . mysqli_error($conexion);
    }
} else {
    $errors[] = "Error desconocido.";
}

if (isset($errors)) {

    ?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
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
        <button type="button" class="close" data-dismiss="alert">&times;</button>
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