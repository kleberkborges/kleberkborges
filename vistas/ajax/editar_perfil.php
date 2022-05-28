<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['nombre_empresa'])) {
    $errors[] = "Nombre de empresa esta vacío";
} else if (empty($_POST['telefono'])) {
    $errors[] = "Teléfono esta vacío";
} else if (empty($_POST['moneda'])) {
    $errors[] = "Moneda esta vacío";
} else if (
    !empty($_POST['nombre_empresa']) &&
    !empty($_POST['telefono']) &&
    !empty($_POST['moneda'])
) {
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    // escaping, additionally removing everything that could be (html/javascript-) code
    $nombre_empresa = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre_empresa"], ENT_QUOTES)));
    $giro           = mysqli_real_escape_string($conexion, (strip_tags($_POST["giro"], ENT_QUOTES)));
    $fiscal         = mysqli_real_escape_string($conexion, (strip_tags($_POST["fiscal"], ENT_QUOTES)));
    $telefono       = mysqli_real_escape_string($conexion, (strip_tags($_POST["telefono"], ENT_QUOTES)));
    $email          = mysqli_real_escape_string($conexion, (strip_tags($_POST["email"], ENT_QUOTES)));
    $nom_impuesto   = mysqli_real_escape_string($conexion, (strip_tags($_POST["nom_impuesto"], ENT_QUOTES)));
    $moneda         = mysqli_real_escape_string($conexion, (strip_tags($_POST["moneda"], ENT_QUOTES)));
    $direccion      = mysqli_real_escape_string($conexion, (strip_tags($_POST["direccion"], ENT_QUOTES)));
    $ciudad         = mysqli_real_escape_string($conexion, (strip_tags($_POST["ciudad"], ENT_QUOTES)));

    $sql = "UPDATE perfil SET nombre_empresa='" . $nombre_empresa . "',
                                            giro_empresa='" . $giro . "',
                                            fiscal_empresa='" . $fiscal . "',
                                            telefono='" . $telefono . "',
                                            email='" . $email . "',
                                            impuesto = 10,
                                            nom_impuesto='" . $nom_impuesto . "',
                                            moneda='" . $moneda . "',
                                            direccion='" . $direccion . "',
                                            ciudad='" . $ciudad . "'
                                            WHERE id_perfil='1'";
    $query_update = mysqli_query($conexion, $sql);
    if ($query_update) {
        $messages[] = "Perfil actualizado correctamente.";
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