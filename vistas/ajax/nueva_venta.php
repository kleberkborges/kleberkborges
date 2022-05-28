<?php
    /** VERIFICAR SI EL USUARIO ESTA LOGEADO */
    require_once 'is_logged.php';

    /** VALIDACION DE DATOS DEL LADO DEL BACKEND */
    if (!empty($_POST['id_cliente'])) {
        /** CONEXION A LA BASE DE DATOS */
        require_once '../db.php';
        require_once '../php_conexion.php';

        /** FUNCIONES PREDEFINIDAS POR EL DESARROLLADOR */
        require_once '../funciones.php';

        $session_id     = session_id();
        $simbolo_moneda = obtener_moneda();

        /** COMPROBAR SI HAY ARCHIVOS EN LA TABLA tmp_ventas */
        $query = mysqli_query($conexion, "SELECT * FROM tmp_ventas WHERE session_id = '$session_id'");
        $count = mysqli_num_rows($query);

        if ($count == 0) {
            echo "<script>
            swal({
                title: 'No hay Productos agregados en la  factura',
                 text: 'Intentar nuevamente',
                type: 'error',
                confirmButtonText: 'ok'
            })</script>";
            exit;
        }

        mysqli_free_result($query);

        /** ESCAPANDO INFORMACION ENVIADA POR EL USUARIO */

        $id_cliente     = intval($_POST['id_cliente']);
        $id_comp        = intval($_POST['id_comp']);
        $id_vendedor    = intval($_SESSION['id_users']);
        $users          = $id_vendedor;
        $condiciones    = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['condiciones'], ENT_QUOTES)));
        $numero_factura = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['factura'], ENT_QUOTES)));
        $trans          = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['trans'], ENT_QUOTES)));
        $date_added     = date("Y-m-d H:i:s");

        $description    = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['descripcion'], ENT_QUOTES)));

        if (isset($_POST['resibido']) && !empty($_POST['resibido']) && $condiciones != 3) {
            $recibido = $_POST['resibido'];
            $recibido = str_replace('.', '', $recibido);
        } else {
            $recibido = 0;
        }

        //Operacion de Creditos
        if ($condiciones == 4) {
            $estado = 2;
            if (!empty($_REQUEST['cantidad_pagos'])) {
                // $restante_credito = floatval($_REQUEST['restante_credito']);
                $cantidad_pagos   = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['cantidad_pagos'], ENT_QUOTES)));
            } else {
                echo "<script>
                swal({
                    title: 'No has llenado completamente el formulario',
                    text: 'Intentar nuevamente',
                    type: 'error',
                    confirmButtonText: 'ok'
                })</script>";
                exit;
            }
        } else {
            $estado = 1;
        }

        /** OBTENER DATOS AGREGADOS EN LA TABLA tmp_ventas */
        $sumador_total = 0;
        $total_factura = 0;
        
        $sql = mysqli_query($conexion, "SELECT * FROM productos, tmp_ventas WHERE productos.id_producto = tmp_ventas.id_producto AND tmp_ventas.session_id='$session_id'");
        
        while ($row = mysqli_fetch_array($sql)) {
            $id_tmp          = $row["id_tmp"];
            $id_producto     = $row['id_producto'];
            $codigo_producto = $row['codigo_producto'];
            $cantidad        = $row['cantidad_tmp'];
            $desc_tmp        = $row['desc_tmp'];
            $nombre_producto = $row['nombre_producto'];
            $iva_producto    = $row['iva_producto'];
            $inv_producto    = $row['inv_producto'];
            /** CONTROL DE IMPUESTOS POR PRODUCTOS */
    
            $precio_venta   = $row['precio_tmp'];
            $costo_producto = $row['costo_producto'];
            /** FORMATEAR VARIABLES */
            $precio_venta = (int)$precio_venta;
            $cantidad = (int)$cantidad;

            $precio_total   = $precio_venta * $cantidad;
            $precio_total_rebajado = rebajas($precio_total, $desc_tmp);
    
            /** CONFIGURAR VARIABLES PARA INSERTAR NUEVA VENTA */
            $total_factura += $precio_total_rebajado;
        
            if ($condiciones != 3) {
                $cambio        = $recibido - $total_factura;
                $restante_credito = $total_factura - $recibido;
                $cambio        = formatear_moneda($simbolo_moneda, $cambio);
                $recibido_f    = formatear_moneda($simbolo_moneda, $recibido);
                /** CONFIGURAR VARIABLES PARA INSERTAR EN TABLA facturas_ventas */
                if ($recibido < $total_factura && $condiciones != 4) {
                    echo "<script>
                    swal({
                        title: 'DINERO RECIBIDO ES MENOR AL MONTO TOTAL',
                        text: 'Intentar Nuevamente',
                        type: 'error',
                        confirmButtonText: 'ok'
                    })</script>";
                    exit;
                }
            } else {
                $recibido = $total_factura;
            }

            if ($inv_producto == 0) {
                $insert_detail = mysqli_query($conexion, "INSERT INTO detalle_fact_ventas VALUES (NULL, 0,'$numero_factura','$id_producto','$cantidad','$desc_tmp','$precio_venta','$precio_total')");
                $query = mysqli_query($conexion, "SELECT * FROM kardex WHERE producto_kardex='$id_producto' ORDER BY id_kardex DESC LIMIT 1");
                
                $rows = mysqli_fetch_array($query);
                mysqli_free_result($query);
                
                $id_producto = $rows['producto_kardex'];
                $saldo_total = $cantidad * $costo_producto;
                $costo_saldo = $rows['costo_saldo'];
                $cant_saldo  = $rows['cant_saldo'] - $cantidad;

                // Casting de variables
                $cant_saldo  = (float)$cant_saldo;
                $costo_producto = (float)$costo_producto;

                $nuevo_saldo = $cant_saldo * $costo_producto;
                $tipo        = 2;
                guardar_salidas($date_added, $id_producto, $cantidad, $costo_producto, $saldo_total, $cant_saldo, $costo_saldo, $nuevo_saldo, $date_added, $users, $tipo);
            }
            
            /** ACTUALIZA EL STOCK */
            $query = mysqli_query($conexion, "SELECT * FROM productos WHERE id_producto = '$id_producto'");
            
            $rows = mysqli_fetch_array($query);
            mysqli_free_result($query);
            
            // Cantidad encontrada en el inventario
            $old_qty = $rows['stock_producto'];
            // Nueva cantidad para inventario
            $new_qty = $old_qty - $cantidad;
            
            $query = mysqli_query($conexion, "UPDATE productos SET stock_producto = '$new_qty' WHERE id_producto = '$id_producto' AND inv_producto = 0");
        }      

            
        /** INSERTAR DATOS EN TABLA facturas_ventas */
        $query = mysqli_query($conexion, "INSERT INTO facturas_ventas VALUES (NULL,'$numero_factura','$date_added','$id_cliente','$id_vendedor','$condiciones','$total_factura','$estado','$users','$recibido','1','$id_comp','$trans', '$description', NULL, NULL)");
        
        $last_inserted_id = mysqli_insert_id($conexion);
        
        if ($condiciones == 4) {
            $query = mysqli_query($conexion, "INSERT INTO creditos VALUES (NULL,'$numero_factura','$date_added','$id_cliente','$id_vendedor','$total_factura','$restante_credito','1','$users','1', '$cantidad_pagos', '$last_inserted_id')");

            $id_credito = mysqli_insert_id($conexion);

            $query = mysqli_query($conexion, "INSERT INTO creditos_abonos VALUES (NULL,'$numero_factura','$date_added','$id_cliente','$total_factura','$recibido','$restante_credito','$users','1','CREDITO INICAL', '$id_credito')");

        } else if ($condiciones == 3) {
            $numero_comprobante = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['numero_comprobante'], ENT_QUOTES)));

            $query = mysqli_query($conexion, "UPDATE facturas_ventas SET numero_comprobante = '$numero_comprobante' WHERE numero_factura = '$numero_factura'");

        } else if ($condiciones == 2) {
            $numero_cheque = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['numero_cheque'], ENT_QUOTES)));

            $query = mysqli_query($conexion, "UPDATE facturas_ventas SET numero_cheque = '$numero_cheque' WHERE numero_factura = '$numero_factura'");
        }

        /** INSERT EN LA TABLA detalle_factura */
        mysqli_query($conexion, "UPDATE detalle_fact_ventas SET id_factura='$last_inserted_id' WHERE numero_factura = '$numero_factura'");
        
        if (mysqli_error($conexion)) {
            $errors[] = "Algo ha salido mal, intentelo nuevamente." . mysqli_error($conexion);
        } else {
            echo "<script>
                $('#outer_comprobante').load('../ajax/carga_correlativos.php');
                $('#resultados5').load('../ajax/carga_num_trans.php')
                $('#modal_vuelto').modal('show');
            </script>";
            $messages[] = "Venta agregada correctamente.";
        }

    } else {
        $errors[] = "ID VACÍO.";
    }

    if (isset($errors) && !empty($errors)) {
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
    } else if (isset($messages) && !empty($messages)) {
        mysqli_query($conexion, "DELETE FROM tmp_ventas");
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
    if ($condiciones != 4 && $condiciones != 3) {
?>
<!-- Modal -->
<div class="modal fade" id="modal_vuelto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='fa fa-edit'></i> FACTURA: <?php echo $numero_factura; ?></h4>
            </div>
            <div class="modal-body" align="center">
                <strong><h3>CAMBIO</h3></strong>
                <div class="alert alert-info" align="center">
                    <strong><h1>
                        <?php echo $simbolo_moneda . ' ' . $cambio; ?>
                    </h1></strong>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="imprimir" class="btn btn-primary btn-block btn-lg waves-effect waves-light" onclick="printOrder('<?=$last_inserted_id?>');" accesskey="t" ><span class="fa fa-print"></span> Ticket</button><br>
                <button type="button" id="imprimir2" class="btn btn-success btn-block btn-lg waves-effect waves-light" onclick="printFactura('<?=$last_inserted_id?>');" accesskey="p"><span class="fa fa-print"></span> Factura</button>
            </div>
        </div>
    </div>
</div>
<?php
    } else if ($condiciones == 4) {
?>
<div class="modal fade" id="modal_vuelto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='fa fa-edit'></i><?php echo $numero_factura; ?></h4>
            </div>
            <div class="modal-body" align="center">
                <strong><h4>VENTA AL CREDITO GUARDADA CON EXITO CON ATICIPO DE:</h4></strong>
                <div class="alert alert-info" align="center">
                    <strong><h1>
                        <?php echo $simbolo_moneda . ' ' . $recibido_f; ?>
                    </h1></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="imprimir" class="btn btn-success btn-block btn-lg waves-effect waves-light" onclick="printOrder('<?=$last_inserted_id?>');" accesskey="t" ><span class="fa fa-print"></span> Ticket</button><br>
                <button type="button" id="imprimir" class="btn btn-success btn-block btn-lg waves-effect waves-light" onclick="printPagare('<?=$last_inserted_id?>');" accesskey="t" ><span class="fa fa-print"></span> Pagaré</button>
            </div>
        </div>
    </div>
</div> 
<?php
    } else if ($condiciones == 3) {
?>
<div class="modal fade" id="modal_vuelto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='fa fa-edit'></i><?php echo $numero_factura; ?></h4>
            </div>
            <div class="modal-body" align="center">
                <strong><h4>VENTA CON TARJETA GUARDADA CON EXITO CON NÚMERO DE COMPROBANTE:</h4></strong>
                <div class="alert alert-info" align="center">
                    <strong><h1>
                        <?php echo $numero_comprobante; ?>
                    </h1></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="imprimir" class="btn btn-success btn-block btn-lg waves-effect waves-light" onclick="printOrder('<?=$last_inserted_id?>');" accesskey="t" ><span class="fa fa-print"></span> Ticket</button><br>
            </div>
        </div>
    </div>
</div> 
<?php
    }
?>