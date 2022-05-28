<?php
if ($conexion) {
    /*Datos de la empresa*/
    $sql           = mysqli_query($conexion, "SELECT * FROM perfil");
    $rw            = mysqli_fetch_array($sql);
    $moneda        = $rw["moneda"];
    $bussines_name = $rw["nombre_empresa"];
    $address       = $rw["direccion"];
    $city          = $rw["ciudad"];
    $phone         = $rw["telefono"];
    $email         = $rw["email"];

/*Fin datos empresa*/
    ?>
    <table cellspacing="0" style="width: 100%;"  border="0">
        <tr>

            <td style="width: 18%;">

            </td>
              <td style="width: 12%;"></td>
            <td style="width: 45%; font-size:12px;text-align:center">
                <span style="font-size:14px;font-weight:bold"><?php echo $bussines_name; ?></span>
                <br><?php echo $address . ', ' . $city ?><br>
                Teléfono: <?php echo $phone; ?><br>
                Email: <?php echo $email; ?>

            </td>
            <td style="width: 30%;text-align:right; color:#ff0000">
FACTURA Nº <?php echo $numero_factura; ?>
            </td>

        </tr>
    </table>
    <?php }?>