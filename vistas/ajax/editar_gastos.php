<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['mod_id'])) {
    $errors[] = "ID vacío";
} else if (
    !empty($_POST['mod_id'])
) {
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    // escaping, additionally removing everything that could be (html/javascript-) code
    $referencia  = mysqli_real_escape_string($conexion, (strip_tags($_POST["mod_referencia"], ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($conexion, (strip_tags($_POST["mod_descripcion"], ENT_QUOTES)));
    $monto       = $_POST['mod_monto'];
    $monto       = str_replace('.', '', $monto);

    $id_egreso = intval($_POST['mod_id']);

    $sql = "UPDATE egresos SET  referencia_egreso='" . $referencia . "',
                                monto='" . $monto . "',
                                descripcion_egreso='" . $descripcion . "'
                                WHERE id_egreso='" . $id_egreso . "'";
    $query_update = mysqli_query($conexion, $sql);
    if ($query_update) {
        $messages[] = "Egreso actualizado correctamente";
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