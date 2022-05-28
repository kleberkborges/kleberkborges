<?php
include '../ajax/is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$numero_factura = $_SESSION['numero_factura'];
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Inicia Control de Permisos
include "../permisos.php";
//Archivo de funciones PHP
require_once "../funciones.php";
//Ontengo variables pasadas por GET
$simbolo_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);
$id_abono       = intval($_REQUEST['id_abono']);
$tables         = "creditos_abonos_prov";
$campos         = "*";
$sWhere         = "id_abono='" . $id_abono . "'";
$sWhere .= " order by id_abono";
$query = mysqli_query($conexion, "SELECT $campos FROM  $tables where $sWhere ");
// get the HTML
ob_start();
include dirname(__FILE__) . '/res/rep_abonop_html.php';
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
    $html2pdf->Output('abono_credito_proveedor.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
