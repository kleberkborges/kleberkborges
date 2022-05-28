<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['codigo'])) {
    $errors[] = "Código vacío";
} else if (empty($_POST['nombre'])) {
    $errors[] = "Nombre del producto vacío";
} else if ($_POST['linea'] == "") {
    $errors[] = "Selecciona una Linea del producto";
} else if ($_POST['proveedor'] == "") {
    $errors[] = "Selecciona un Proveedor";
} else if (empty($_POST['costo'])) {
    $errors[] = "Costo de Producto vacío";
} else if (empty($_POST['precio'])) {
    $errors[] = "Precio de venta vacío";
} else if (empty($_POST['minimo'])) {
    $errors[] = "Stock minimo  vacío";
} else if ($_POST['estado'] == "") {
    $errors[] = "Selecciona el estado del producto";
} else if ($_POST['impuesto'] == "") {
    $errors[] = "Selecciona el impuesto del producto";
} else if ($_POST['inv'] == "") {
    $errors[] = "Selecciona Maneja Inventario";
} else if (empty($_POST['unidad_medida'])) {
    $errors[] = "Selecciona Unidad de medida";
}else if (
    !empty($_POST['codigo']) &&
    !empty($_POST['nombre']) &&
    $_POST['linea'] != "" &&
    $_POST['proveedor'] != "" &&
    $_POST['estado'] != "" &&
    $_POST['impuesto'] != "" &&
    $_POST['inv'] != "" &&
    !empty($_POST['costo']) &&
    !empty($_POST['precio']) &&
    !empty($_POST['minimo']) &&
    !empty($_POST['unidad_medida'])
) {
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    //Archivo de funciones PHP
    require_once "../funciones.php";

    // escaping, additionally removing everything that could be (html/javascript-) code
    $moneda = obtener_moneda();
    $codigo      = mysqli_real_escape_string($conexion, (strip_tags($_POST["codigo"], ENT_QUOTES)));
    $nombre      = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre"], ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($conexion, (strip_tags($_POST["descripcion"], ENT_QUOTES)));
    $linea       = intval($_POST['linea']);
    $proveedor   = intval($_POST['proveedor']);
    $estado      = intval($_POST['estado']);
    $impuesto    = intval($_POST['impuesto']);
    $inv         = intval($_POST['inv']);

    if($inv == '1') {
        $stock = 0;
    } else {
        $stock = floatval($_POST['stock']);
    }
    //$imp              = intval($_POST['id_imp']);
    $costo            = str_replace('.', '',$_POST['costo']);
    
    $utilidad         = floatval($_POST['utilidad']);

    $precio_venta     = str_replace('.', '',$_POST['precio']);
    $precio_mayoreo   = str_replace('.', '',$_POST['preciom']);
    $stock_minimo     = floatval($_POST['minimo']);
    $date_added       = date("Y-m-d H:i:s");
    $users            = intval($_SESSION['id_users']);
    $unidad_medida    = mysqli_real_escape_string($conexion, (strip_tags($_POST['unidad_medida'], ENT_QUOTES)));
    $query_new_insert = '';
// check if user or email address already exists
    $sql                   = "SELECT * FROM productos WHERE codigo_producto ='" . $codigo . "';";
    $query_check_user_name = mysqli_query($conexion, $sql);
    $query_check_user      = mysqli_num_rows($query_check_user_name);
    if ($query_check_user == true) {
        $sql = "UPDATE productos SET codigo_producto='" . $codigo . "',
                                        nombre_producto='" . $nombre . "',
                                        descripcion_producto='" . $descripcion . "',
                                        id_linea_producto='" . $linea . "',
                                        id_proveedor='" . $proveedor . "',
                                        inv_producto='" . $inv . "',
                                        iva_producto='" . $impuesto . "',
                                        estado_producto='" . $estado . "',
                                        costo_producto='" . $costo . "',
                                        utilidad_producto='" . $utilidad . "',
                                        valor1_producto='" . $precio_venta . "',
                                        valor2_producto='" . $precio_mayoreo . "',
                                        stock_producto='" . $stock . "',
                                        stock_min_producto='" . $stock_minimo . "',
                                        unidad_medida = '$unidad_medida'
                                        WHERE codigo_producto='" . $codigo . "'";
        $query_update = mysqli_query($conexion, $sql);
    } else {
        $sql = "INSERT INTO productos (codigo_producto, nombre_producto, descripcion_producto, id_linea_producto, id_proveedor, inv_producto, iva_producto, estado_producto, costo_producto, utilidad_producto, valor1_producto,valor2_producto, stock_producto,stock_min_producto, date_added,id_imp_producto, unidad_medida) VALUES ('$codigo','$nombre','$descripcion','$linea','$proveedor','$inv','$impuesto','$estado','$costo','$utilidad','$precio_venta','$precio_mayoreo','$stock','$stock_minimo','$date_added','0', '$unidad_medida')";
        $query_new_insert = mysqli_query($conexion, $sql);
        
        /** SELECCIONAMOS EL ÚLTIMO ID INSERTADO */
        $id_producto = mysqli_insert_id($conexion);
    }
    //GUARDAMOS LAS ENTRADAS EN EL KARDEX
    if($inv == 0) {
        $saldo_total    = $stock * $costo;
        $cant_saldo     = $stock;
        $saldo_full     = $saldo_total;
        $costo_promedio = $saldo_total / $cant_saldo;
        $tipo           = 5;
        guardar_entradas($date_added, $id_producto, $stock, $costo, $saldo_total, $cant_saldo, $costo_promedio, $saldo_full, $date_added, $users, $tipo);
    }

    
    if ($query_new_insert or $query_update) {
        $messages[] = "Producto agregado correctamente.";
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