<?php
    $tiempo_transcurrido = time();
    $tiempo_maximo = $_SESSION['inicio'] + ($_SESSION['intervalo'] * 60);

    if ($tiempo_transcurrido > $tiempo_maximo) {
        session_destroy();
        header('location: ../../login.php');
    }