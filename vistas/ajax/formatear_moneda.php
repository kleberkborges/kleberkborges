<?php

    if(isset($_POST['precio']) && !empty($_POST['precio'])) {
        $precio = $_POST['precio'];
        if(is_numeric($precio)){
            $precio = str_replace('.', '', $precio);
            $precio_formateado = number_format($precio, 0, '',  '.');
            echo $precio_formateado;
        } else {
            echo $precio;
        }
    } else if($_POST['precio'] == 0) {
        echo 0;
    }

?>