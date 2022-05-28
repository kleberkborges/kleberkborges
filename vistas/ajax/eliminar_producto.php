<?php
/*-----------------------
Autor: Delmar Lopez
http://www.softwys.com
----------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Clientes";
permisos($modulo, $cadena_permisos);
/*Inicia validacion del lado del servidor*/
if (empty($_POST['id_producto'])) {
    $errors[] = "ID vacío";
} else if (
    !empty($_POST['id_producto'])

) {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $id_producto = intval($_POST['id_producto']);
    // $query       = mysqli_query($conexion, "SELECT * FROM facturas WHERE id_producto='$id_producto'");
    // var_dump($id_producto);
    // $count       = mysqli_num_rows($query);
    $count = 0;
    if ($count == 0) {
        if ($delete1 = mysqli_query($conexion, "DELETE FROM productos WHERE id_producto='" . $id_producto . "'")) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <strong>¡Aviso!</strong> Datos eliminados correctamente.
          </div>
          <?php
} else {
            ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <strong>¡Error!</strong> Algo ha salido mal, intentalo nuevamente.
      </div>
      <?php

        }

    } else {
        ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
      <strong>¡Error!</strong> No se pudo eliminar este Producto. Existe información vinculada.
  </div>
  <?php
}

} else {
    $errors[] = "Error desconocido.";
}

if (isset($errors)) {

    ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error!</strong>
        <?php
foreach ($errors as $error) {
        echo $error;
    }
    ?>
    </div>
    <?php
}

?>