<style type="text/css">
  
  table {
    vertical-align: top;
    border-collapse: collapse;
  }
  tr    { vertical-align: top; }
  td    { vertical-align: top; }
  th    {
    text-align:  center;
    border: solid #fff 1px;
  }
  .midnight-blue{
    background:#2c3e50;
    padding: 4px 4px 4px;
    color:white;
    font-weight:bold;
    font-size:12px;
  }
  .silver{
    background:white;
    padding: 3px 4px 3px;
  }
  .clouds{
    background:#ecf0f1;
    padding: 3px 4px 3px;
  }
  .border-top{
    border-top: solid 1px #bdc3c7;

  }
  .border-left{
    border-left: solid 1px #bdc3c7;
  }
  .border-right{
    border-right: solid 1px #bdc3c7;
  }
  .border-bottom{
    border-bottom: solid 1px #bdc3c7;
  }
  table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
  .border{
    border: solid #e5e5e5 1px;
  }
  .text-center{
    text-align: center;
  }

</style>
<page pageset='new' backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm' style="font-size: 13px; font-family: helvetica">
  <page_header>
  <table style="width: 100%; border: solid 0px black;" cellspacing=0>
    <tr>
      <td style="text-align: left;    width: 25%"></td>
      <td style="text-align: center;    width: 50%;font-size: 14px; font-weight: bold">Reporte de Clientes</td>
      <td style="text-align: right;    width: 25%"><?php echo date('d/m/Y'); ?></td>
    </tr>
  </table>
  </page_header>
  <?php include "encabezado_general.php";?>

  <table class="table-bordered" style="width:100%;">
    <tr class="midnight-blue">
      <th style="width:4%;">ID</th>
      <th style="width:15%;">NOMBRE</th>
      <th style="width:11%;">RUC</th>
      <th style="width:11%;">TELÉFONO</th>
      <th style="width:15%;">EMAIL</th>
      <th style="width:25%;">DIRECCIÓN</th>
      <th style="width:10%;">FECHA</th>
      <th style="width:9%;">ESTADO</th>
    </tr>
    <?php
$sumador_total  = 0;
$simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
while ($row = mysqli_fetch_array($query)) {
    $codigo            = $row['id_cliente'];
    $nombre_cliente    = $row['nombre_cliente'];
    $fiscal_documento  = $row['fiscal_cliente'];
    $telefono_cliente  = $row['telefono_cliente'];
    $email_cliente     = $row['email_cliente'];
    $direccion_cliente = $row['direccion_cliente'];
    $status_cliente    = $row['status_cliente'];
    $date_added        = date('d/m/Y', strtotime($row['date_added']));

    if ($status_cliente == 1) {
        $estado = "Activo";
    } else {
        $estado = "Inactivo";
    }
    ?>
    <tr>
     <td class='text-center border'><?php echo $codigo; ?></td>
     <td class='text-left border'><?php echo $nombre_cliente; ?></td>
     <td class='text-center border'><?php echo $fiscal_documento; ?></td>
     <td class='text-center border'><?php echo $telefono_cliente ?></td>
     <td class='text-center border'><?php echo $email_cliente ?></td>
     <td class='text-left border'><?php echo $direccion_cliente ?></td>
     <td class='text-center border'><?php echo $date_added ?></td>
     <td class='text-center border'><?php echo $estado ?></td>
   </tr>
   <?php
}

?>
</table>
<page_footer>
<table style="width: 100%; border: solid 0px black;">
  <tr>
    <td style="text-align: left;    width: 50%"></td>
    <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
  </tr>
</table>
</page_footer>
</page>