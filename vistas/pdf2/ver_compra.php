<?php
/*-------------------------
Autor: Obed Alvarado
Web: obedalvarado.pw
Mail: info@obedalvarado.pw
---------------------------*/
session_start();
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
    header("location: ../../login.php");
    exit;
}

/* Connect To Database*/
include "../db.php";
include "../php_conexion.php";
//Archivo de funciones PHP
include "../funciones.php";
$id_factura = intval($_GET['id_factura']);
$sql_count  = mysqli_query($conexion, "SELECT * FROM facturas_compras WHERE id_factura = '$id_factura'");
$count      = mysqli_num_rows($sql_count);
if ($count == 0) {
    echo "<script>alert('Factura no encontrada')</script>";
    echo "<script>window.close();</script>";
    exit;
}
$sql_factura    = mysqli_query($conexion, "SELECT * FROM facturas_compras WHERE id_factura = '$id_factura'");
$rw_factura     = mysqli_fetch_array($sql_factura);
$numero_factura = $rw_factura['numero_factura'];
$id_proveedor   = $rw_factura['id_proveedor'];
$id_vendedor    = $rw_factura['id_vendedor'];
$fecha_factura  = $rw_factura['fecha_factura'];
$condiciones    = $rw_factura['condiciones'];
$simbolo_moneda = obtener_moneda();
// get the HTML
ob_start();
include dirname(__FILE__) . '/res/ver_compra_html.php';
$content = ob_get_clean();

// convert to PDF
require_once "../../vendor/autoload.php";
use Spipu\Html2Pdf\Html2Pdf;
try
{
    $html2pdf = new Html2Pdf('P', 'A4', 'es');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    ob_end_clean();
    $html2pdf->Output('compra.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
