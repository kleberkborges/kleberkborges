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
  table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;
  }
  .border{
    border: solid #e5e5e5 1px;
  }
  .text-center{
    text-align: center;
  }
  .text-right{
    text-align: right;
  }
  .text-left{
    text-align: left;
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
  <br>
  <div>
    Categoria:
    <?php
$sql1             = mysqli_query($conexion, "SELECT * FROM lineas WHERE id_linea='$id_categoria'");
$rw1              = mysqli_fetch_array($sql1);
if (empty($rw1)) {
  echo "Todos";
} else {
  $nombre_categoria = $rw1['nombre_linea'];
  echo $nombre_categoria;
}
?>
  </div>

  <table class="table-bordered" style="width:100%;">
    <tr class="midnight-blue">
      <th style="width:10%;">Codigo</th>
      <th style="width:20%;">Nombre</th>
      <th style="width:20%;">Categoria</th>
      <th style="width:10%;">Stock</th>
      <th style="width:10%;">Costo </th>
      <th style="width:10%;">Precio V. </th>
      <th style="width:10%;">Precio M. </th>
      <th style="width:10%;">Total Costo</th>
    </tr>
    <?php
$sumador_total  = 0;
$moneda = obtener_moneda();
while ($row = mysqli_fetch_array($query)) {
    $codigo           = $row['codigo_producto'];
    $nombre_producto  = $row['nombre_producto'];
    $nombre_linea     = $row['nombre_linea'];
    $stock_producto   = $row['stock_producto'];
    $costo_producto   = $row['costo_producto'];
    $precio_venta     = $row['valor1_producto'];
    $precio_mayorista = $row['valor2_producto'];
    $estado_producto  = $row['estado_producto'];
    $date_added       = date('d/m/Y', strtotime($row['date_added']));
    /*$sql               = mysqli_query($conexion, "select nombre_cliente from clientes where id_cliente='" . $id_cliente . "'");
    $rw                = mysqli_fetch_array($sql);
    $cliente           = $rw['nombre_cliente'];*/
    if ($estado_producto == 1) {
        $estado = "<label class='label label-success'>Activo</label>";
    } else {
        $estado = "<label class='label label-danger'>Inactivo</label>";
    }
    $total_costo = $stock_producto * $costo_producto;
    $sumador_total += $total_costo;
    ?>
    <tr>
     <td class='text-center border'><label class='label label-purple'><?php echo $codigo; ?></label></td>
     <td class='text-left border'><?php echo $nombre_producto; ?></td>
     <td class='text-left border'><?php echo $nombre_linea; ?></td>
     <td class='text-center border'><?php echo $stock_producto ?></td>
     <td class='text-left border'><?php echo $moneda . '' . formatear_moneda($moneda, $costo_producto); ?></td>
     <td class='text-left border'><?php echo $moneda . '' . formatear_moneda($moneda, $precio_venta); ?></td>
     <td class='text-left border'><?php echo $moneda . '' . formatear_moneda($moneda, $precio_mayorista); ?></td>
     <td class='text-left border'><?php echo $moneda . '' . formatear_moneda($moneda, $total_costo); ?></td>
   </tr>
   <?php
}

?>
 <tr>
  <td style='text-align:right;border-top:3px solid #2c3e50;padding:4px;padding-top:4px;font-size:14px' colspan="8"><?php echo $moneda . '' . formatear_moneda($moneda, $sumador_total) ?></td>
</tr>
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