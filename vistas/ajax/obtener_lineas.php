<?php

    require_once 'is_logged.php';
    require_once "../db.php";
    require_once "../php_conexion.php";

    include "../permisos.php";
    $user_id = $_SESSION['id_users'];
    get_cadena($user_id);
    $modulo = "Proveedores";
    permisos($modulo, $cadena_permisos);

        $query = mysqli_query($conexion, "SELECT id_linea, nombre_linea FROM lineas ORDER BY nombre_linea");

        $data = [];
        while($row = mysqli_fetch_assoc($query)){
            array_push($data, $row);
        }

        

        echo json_encode($data);

?>