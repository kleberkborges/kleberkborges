<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/
if (empty($_POST['nombre']) || empty($_POST['color'])) {
    $errors[] = "Nombre o Color vacío";
} else if (!empty($_POST['nombre']) && !empty($_POST['color'])) {
    /* Connect To Database*/
    require_once "../db.php";
    require_once "../php_conexion.php";
    // escaping, additionally removing everything that could be (html/javascript-) code
    $nombre  = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre"], ENT_QUOTES)));
    $color   = mysqli_real_escape_string($conexion, (strip_tags($_POST["color"], ENT_QUOTES)));

    // check if user or email address already exists
    $sql     = "SELECT * FROM estados_servicios WHERE nombre = '$nombre';";
    $query_check = mysqli_query($conexion, $sql);
    $query_check = mysqli_num_rows($query_check);
    if ($query_check == true) {
        $errors[] = "Nombre de Estado de servicio ya está en uso.";
    } else {
        // write new user's data into database

        $query = mysqli_query($conexion, "INSERT INTO estados_servicios (nombre, color) VALUES ('$nombre', '$color')");

        if ($query) {
            $messages[] = "Estado de servicio agregado correctamente.";
        } else {
            $errors[] = "Algo ha salido mal, intentalo nuevamente. " . mysqli_error($conexion);
        }
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