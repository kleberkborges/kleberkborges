<?php

    session_start();
    if (!isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] != 1) {
        header("location: ../../login.php");
        exit;
    }
    require_once "includes/session_time.php";
    
    /* Connect To Database*/
    require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
    require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos

    //Inicia Control de Permisos
    require_once "includes/session_time.php";
    include "../permisos.php";
    $user_id = $_SESSION['id_users'];
    get_cadena($user_id);
    $modulo = "Cotizacion";
    permisos($modulo, $cadena_permisos);
    //Finaliza Control de Permisos
    require_once "../funciones.php";

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="David Diaz">

        <link rel="shortcut icon" href="assets/images/favicon.png">

        <title>ENJOI</title>

        <link href="../../../plugins/switchery/switchery.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


        <link href="../../assets/css/bootstrap4.min.css" rel="stylesheet" type="text/css">
        <link href="../../assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="../../assets/css/style.css" rel="stylesheet" type="text/css">

        <script src="../../assets/js/modernizr.min.js"></script>

    </head>
    <body class="h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 vh-100">
            <div class="col-md-10 align-self-center text-center">
                <div class="alert alert-danger" role="alert">
                    <strong>¡Aviso!</strong>
                    <p>Debes ingresar la cotizacion del día de las 2 monedas para poder utilizar el sistema.</p>
                    <p>Presiona el botón 'Listo' cuando hayas terminado</p>
                </div>
                <form class="form-horizontal card-box" method="post" id="guardar_cotizacion" name="guardar_cotizacion">
                    <h5 class="text-dark  header-title m-t-0 m-b-30">Cotización del día</h5>

                    <div class="widget-chart text-center">
                        <div id="resultados_ajax"></div>
                            <div class="column">
                            
                                <div class='row justify-content-center'>
                                    <div class='col-md-6'>
                                        <label for="cotizacion" class="pull-left">Cotización</label>
                                        <input type="number" class="form-control" id="cambio" name="cambio" autocomplete="off" placeholder="0" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="deMoneda">De</label>
                                        <select class="form-control" name="deMoneda" id="deMoneda" title="Selecciona moneda a cotizar" required>
                                            <option value="">-- Selecciona --</option>
                                            <?php
                                            $monedas = obtener_codigos_monedas();
                                            foreach ($monedas as $moneda) {
                                                echo "
                                                <option value=".$moneda['id'].">".$moneda['code']."</option>
                                                ";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="aMoneda">A</label>
                                        <select class="form-control" name="aMoneda" id="aMoneda" title="Selecciona moneda base" required>
                                    <?php
                                        $moneda = obtener_codigo_moneda_actual();
                                        echo "<option value=".$moneda['id'].">".$moneda['code']."</option>";
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row col-xs-12 justify-content-end">
                                    <button type="button" class="btn btn-primary waves-effect waves-light mr-2 mt-2" id="listo">Listo</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mt-2" id="guardar_cotizacion_btn">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>  
</body>
<script type="text/javascript" defer>
    let guardar_cotizacion_btn = $('#guardar_cotizacion_btn');
    let resultados_ajax = $("#resultados_ajax");

    let listo_btn = $("#listo");

    guardar_cotizacion_btn.click((e)=>{
        e.preventDefault();
        let formData = new FormData(document.getElementById('guardar_cotizacion'));

        fetch('../ajax/guardar_cotizacion_moneda.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            resultados_ajax.html(data);
            $('#guardar_cotizacion')[0].reset();
            setTimeout(() => {
                $("#alert").fadeTo(500, 0).slideUp(500, () => {
                    $(this).remove();
                });
            }, 5000);

        })
        .catch(err => {
            console.log(err);
            resultados_ajax.append("<div class='alert alert-danger' role='alert'>");
            $('.alert').html(`
            <strong>¡Error!</strong>
            <p>Ha ocurrido un error, intentalo nuevamente.</p>
            `);
            setTimeout(() => {
                $("#alert").fadeTo(500, 0).slideUp(500, () => {
                    $(this).remove();
                });
            }, 5000);
        });
    });

    listo_btn.click((e)=>{
        e.preventDefault();
        location.pathname = "/sistema/vistas/html/new_venta.php";
    });
</script>
</html>