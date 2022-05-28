<?php
    require_once 'is_logged.php';
    require_once "../db.php";
    require_once "../php_conexion.php";

    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE id_cliente = '$id'");
        $rows = mysqli_fetch_assoc($query);
        echo json_encode($rows);
    } else {
        echo 'No has proporcionado Id';
    }
?>