<page pageset='new' backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm' footer='page'>
<?php include "encabezado_general.php";?><br>
  <div style='border-bottom: 3px solid #2874A6;padding-bottom:10px'>
  </div>

  <table cellpadding='4' cellspacing='0' border='0'>
    <tr class='midnight-blue'>
      <th style="width:15%;">Referencia </th>
      <th style="width:25%;">Descripción</th>
      <th style="width:15%;">Fecha</th>
      <th style="width:15%;">Monto</th>
      <th style="width:20%;">Usuario</th>
    </tr>
    <?php
$simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
$sumador_total  = 0;
//consulta de la anterior que manda la pagina anteior
while ($row = mysqli_fetch_array($query)) {
    $referencia  = $row['referencia_egreso'];
    $Descripcion = $row['descripcion_egreso'];
    $id_users    = $row['users'];
    //otra consulta para el nombre del paciente
    $sql          = mysqli_query($conexion, "select nombre_users, apellido_users from users where id_users='" . $id_users . "'");
    $rw           = mysqli_fetch_array($sql);
    $nombre_users = $rw['nombre_users'] . ' ' . $rw['apellido_users'];
    // fin consulta
    $date_added = $row['fecha_added'];
    $total      = $row['monto'];

    $sumador_total += $total;

    list($date)      = explode(" ", $date_added);
    list($Y, $m, $d) = explode("-", $date);
    $fecha           = $d . "-" . $m . "-" . $Y;
    //$nombre_gasto    = get_row('tipo_gasto', 'nombre_tipo', 'id_tipo', $tipo_gasto);
    ?>
      <tr>
        <td><?php echo $referencia; ?></td>
        <td><?php echo $Descripcion; ?></td>
        <td><?php echo $fecha; ?></td>
        <td><?php echo $simbolo_moneda . '' . formatear_moneda($simbolo_moneda, $total) ?></td>
        <td><?php echo $nombre_users; ?></td>
      </tr>
      <?php
}

?>
    <tr>
    <td style='text-align:right;border-top:3px solid #2874A6;padding:4px;padding-top:4px;font-size:14px' colspan="4"><?php echo $simbolo_moneda . '' . formatear_moneda($simbolo_moneda, $sumador_total) ?></td>
    </tr>
  </table>
</page>

