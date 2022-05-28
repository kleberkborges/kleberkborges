<?php

    require_once 'is_logged.php';
    require_once "../db.php";
    require_once "../php_conexion.php";

    $query = mysqli_query($conexion, "SELECT id, nombre FROM estados_servicios ORDER BY nombre");

    $data = [];
    while($row = mysqli_fetch_assoc($query)){
        array_push($data, $row);
    }
    echo json_encode($data);

?>