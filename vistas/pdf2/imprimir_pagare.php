<?php
    //Archivo verifica que el usario que intenta acceder a la URL esta logueado
    include '../ajax/is_logged.php';
    require_once "../db.php";
    require_once "../php_conexion.php";
    require_once "../moneda.php";
    $simbolo_moneda = obtener_moneda();
    $users = intval($_SESSION['id_users']);

    $last_id_sql = mysqli_query($conexion, "SELECT MAX(id_factura) AS 'last' FROM facturas_ventas WHERE id_users_factura = '$users'");
    $row = mysqli_fetch_array($last_id_sql);
    $last_id = $row['last'];

    $query = mysqli_query($conexion, "SELECT DATE(factura.fecha_factura) AS 'fecha', factura.monto_factura, factura.dinero_resibido_fac, c.nombre_cliente, c.fiscal_cliente, c.telefono_cliente, c.direccion_cliente FROM facturas_ventas factura
    INNER JOIN clientes c ON factura.id_cliente = c.id_cliente
    WHERE factura.id_factura = '$last_id'");

    $rows = mysqli_fetch_array($query);
    $monto = $rows['monto_factura'] - $rows['dinero_resibido_fac'];
    $fecha = $rows['fecha'];
    $nombre_cliente = $rows['nombre_cliente'];
    $fiscal_cliente = $rows['fiscal_cliente'];
    $telefono_cliente = $rows['telefono_cliente'];
    $direccion_cliente = $rows['direccion_cliente'];

    $query_producto = mysqli_query($conexion, "SELECT factura.cantidad, p.unidad_medida, p.nombre_producto FROM detalle_fact_ventas factura INNER JOIN productos p ON factura.id_producto = p.id_producto WHERE factura.id_factura = '$last_id'");
    $rows = mysqli_fetch_array($query_producto);
    $nombre_producto = $rows['nombre_producto'];
    $unidad_medida = $rows['unidad_medida'];
    $cantidad = $rows['cantidad'];

    $query_domicilio = mysqli_query($conexion, "SELECT nombre_empresa, direccion FROM perfil");
    $row = mysqli_fetch_array($query_domicilio);
    $domicilio = $row['direccion'];
    $empresa = $row['nombre_empresa'];

    $query_cantidad_veces = mysqli_query($conexion, "SELECT cantidad_pagos FROM creditos WHERE id_factura = '$last_id'");
    $row = mysqli_fetch_array($query_cantidad_veces);
    $cantidad_pagos = $row['cantidad_pagos'];

    $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

    $date = explode('-', $fecha);

?>
<style type="text/css" media="print">
    #Imprime {
        height: auto;
        width: 100%;
        margin: 0px;
        padding: 0px;
        float: left;
        font-family: "Comic Sans MS", cursive;
        font-size: 5px;
        font-style: normal;
        line-height: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        color: #000;
    }
    @page{
        margin: 0;
    }

    /* General */
    *{
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica;
    }
    .container{
        padding: 10px;
        height: 48.1vh;
    }
    p{
        line-height : 25px;
    }

    /* Cabecera */
    .header{
        margin-bottom: 10px;
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    /* Cuerpo */
    .lugar_fecha{
        text-align: center;
    }

    /* Footer */
    .footer{
        margin-top: 10px;
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-end;
    }

    /* Extras */
    .uppercase{
        text-transform: uppercase;
    }
    .bold{
        font-weight: bold;
    }
    .border{
        border: solid black 1px;
        padding: 1px 5px;
    }
    .underline{
        text-decoration: underline;
    }
    .largo{
        width: 100px;
    }
    .centrado{
        text-align: center;
    }
    .dashed{
        width: 100%;
        border: dashed black 1px;
    }
</style>
<?php for ($i=1; $i <= $cantidad_pagos; $i++) { ?>
    <div class="container">
        <div class="header">
            <div>
                <p>Nº <span class="border"><?=$i?> / <?=$cantidad_pagos?></span></p>
            </div>
            <div>
                <h2 class="title">Pagaré a la orden</h2>
            </div>
            <div>
                <p>Por <span class="border">Gs.<?=formatear_moneda($simbolo_moneda, $monto/$cantidad_pagos)?></span></p>
            </div>
        </div>
        <div class="body">
            <p class="lugar_fecha">Santa Rita, <?=$date[2] . ' de ' . $meses[$date[1]] . ' de ' . $date[0]?></p>
            <p>El día <span class="border">
            <?php
                if($date[1] + $i <= 11) {
                    $mes = $meses[$date[1] + $i];
                } else {
                    if($i == 1) {
                        $mes = $meses[0 + $i];
                    } else if($i > 1) {
                        $k = 0;
                        $mes = $meses[0 + $k];
                        $k++;
                    }
                }
                echo $date[2] . ' de ' . $mes . ' de ' . $date[0];
            ?>
            </span>, pagaré sin protesto a <span class="underline"><?=$empresa?></span>, o a su orden en su domicilio <span class="underline"><?=$domicilio?></span>, la suma de</p>
            <div class="border">
                <p class="uppercase bold"><?=formatear_moneda($simbolo_moneda, $monto/$cantidad_pagos)?></p>
            </div>
            <p>Por igual valor recibido en pago por servicios realizados a mi entera satisfacción, si este documento no fuere pagado a su vencimientos, devengará un interés punitório del   % mensual en concepto de cláusula penal (art. 454 C.C) desde la mora, que se producirá automáticamente (art. 424 C.C), sin necesidad de interpelación de regreso (art. 1349 C.C). Autorizo la inclusión de mi nombre o razón social que represento a la base de datos <span class="bold">Informconf</span>, conforme a lo establecido en la ley 1682/01. Como también para que se pueda proveer la información a terceros, interesados. Las partes constituyen domicilio especial en los lugares fijados en el presente documento (art. 62 C.C) y aceptan la jurisdicción de los Tribunales de Santa Rita.</p>
            <p class="bold">En concepto de:</p>
            <div class="border">
                <p>Compra de <?=$cantidad . ' ' . $unidad_medida?> de <?=$nombre_producto?></p>
            </div>
        </div>
        <div class="footer">
            <table> 
                <tr>
                    <td class="largo">Nombre:</td>
                    <td><?=$nombre_cliente?></td>
                </tr>
                <tr>
                    <td class="largo">C.I:</td>
                    <td><?=$fiscal_cliente?></td>
                </tr>
                <tr>
                    <td class="largo">Dirección:</td>
                    <td><?$direccion_cliente?></td>
                </tr>
                <tr>
                    <td class="largo">Teléfono:</td>
                    <td><?=$telefono_cliente?></td>
                </tr>
            </table>
            <div class="firma">
                <p>___________________________</p>
                <p class="centrado">Firma</p>
            </div>
        </div>
    </div>
    <div class="dashed"></div>
<?php } ?>