<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$id_factura = $_SESSION['id_factura'];
/*Inicia validacion del lado del servidor*/
if (empty($_POST['id_proveedor'])) {
    $errors[] = "ID vacío";
} else if (empty($_POST['condiciones'])) {
    $errors[] = "Selecciona forma de pago";
} else if ($_POST['estado_factura'] == "") {
    $errors[] = "Selecciona el estado de la factura";
} else if (
    !empty($_POST['id_proveedor']) &&
    !empty($_POST['condiciones']) &&
    $_POST['estado_factura'] != ""
) {
    /* Connect To Database*/
    require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
    require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
    // escaping, additionally removing everything that could be (html/javascript-) code
    $id_proveedor = intval($_POST['id_proveedor']);
    $condiciones  = intval($_POST['condiciones']);
    $factura      = $_POST['factura'];
    $estado_factura = intval($_POST['estado_factura']);
    $users        = intval($_SESSION['id_users']);


    if($condiciones == 1) {
        $sql = "UPDATE facturas_compras SET id_proveedor = '$id_proveedor', numero_factura = '$factura', condiciones = '$condiciones', estado_factura = '$estado_factura' WHERE id_factura = '$id_factura'";
    } else if($condiciones == 2) {
        $numero_cheque = $_POST['numero_cheque'];
        $sql = "UPDATE facturas_compras SET id_proveedor = '$id_proveedor', numero_factura = '$factura', condiciones = '$condiciones', estado_factura = '$estado_factura', numero_cheque = '$numero_cheque' WHERE id_factura = '$id_factura'";
    } else if($condiciones == 3) {
        $numero_comprobante = $_POST['numero_comprobante'];
        $sql = "UPDATE facturas_compras SET id_proveedor = '$id_proveedor', numero_factura = '$factura', condiciones = '$condiciones', estado_factura = '$estado_factura', numero_comprobante = '$numero_comprobante' WHERE id_factura = '$id_factura'";
    } else if($condiciones == 4) {
        $entregado = $_POST['entregado'];
        $entregado = str_replace('.', '', $entregado);
        $cantidad_pagos = $_POST['cantidad_pagos'];

        $sql = "UPDATE facturas_compras SET id_proveedor = '$id_proveedor', numero_factura = '$factura', condiciones = '$condiciones', estado_factura = '$estado_factura' WHERE id_factura = '$id_factura'";
        $query = mysqli_query($conexion, "SELECT * FROM credito_proveedor WHERE id_factura = '$id_factura'");
        if($query){
            $get = mysqli_query($conexion, "SELECT monto_factura, fecha_factura FROM facturas_compras WHERE id_factura = '$id_factura'");

            $row = mysqli_fetch_assoc($get);
            $total_factura = $row['monto_factura'];
            $saldo_credito = $total_factura - $entregado;

            $id_credito = mysqli_query($conexion, "SELECT id_credito FROM credito_proveedor WHERE id_factura = '$id_factura'");
            $row = mysqli_fetch_assoc($id_credito);
            $id_credito = $row['id_credito'];

            $update_creditos = mysqli_query($conexion, "UPDATE credito_proveedor SET numero_factura = '$factura', id_proveedor = '$id_proveedor', monto_credito = '$total_factura', saldo_credito = '$saldo_credito', cantidad_pagos = '$cantidad_pagos' WHERE id_factura = '$id_factura'");

            $update_abonos = mysqli_query($conexion, "UPDATE creditos_abonos_prov SET numero_factura = '$factura', id_proveedor = '$id_proveedor', monto_abono = '$total_factura', abono = '$entregado', saldo_abono = '$saldo_credito', id_users_abono = '$users' WHERE concepto_abono = 'CREDITO INICIAL' AND id_credito = '$id_credito'");
        } else if(!$query) {
            $get = mysqli_query($conexion, "SELECT monto_factura, fecha_factura FROM facturas_compras WHERE id_factura = '$id_factura'");
            $result = mysqli_fetch_assoc($get);

            $total_factura = $result['monto_factura'];
            $fecha = $result['fecha_factura'];
            $saldo_credito = $total_factura - $entregado;
            
            $insert_prima = mysqli_query($conexion, "INSERT INTO credito_proveedor VALUES (NULL,'$factura','$fecha','$id_proveedor','$total_factura','$saldo_credito','1','$users','1', '$cantidad_pagos', '$id_factura'");

            $last_credito = mysqli_insert_id($conexion);

            $insert_abono = mysqli_query($conexion, "INSERT INTO creditos_abonos_prov VALUES (NULL,'$factura','$fecha','$id_proveedor','$total_factura','$entregado','$saldo_credito','$users','1','CREDITO INICIAL', $last_credito)");
        }
    }

    $query_update = mysqli_query($conexion, $sql);
    if ($query_update) {
        $messages[] = "Factura de compra actualizada correctamente.";
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