<?php
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Inicia Control de Permisos
include "../permisos.php";
//Archivo de funciones PHP
require_once "../funciones.php";
//Ontengo variables pasadas por GET
$daterange = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['daterange'], ENT_QUOTES)));
$tables    = "caja_chica,  users";
$campos    = "*";
$sWhere    = "users.id_users=caja_chica.users_caja";
if (isset($_REQUEST['tipo_caja']) && !empty($_REQUEST['tipo_caja'])){
    $tipo_caja = intval($_REQUEST['tipo_caja']);
    if ($tipo_caja > 0) {
        $sWhere .= " and caja_chica.tipo_caja = '" . $tipo_caja . "' ";
    }
}
if (!empty($daterange)) {
    list($f_inicio, $f_final)                    = explode(" - ", $daterange); //Extrae la fecha inicial y la fecha final en formato espa?ol
    list($dia_inicio, $mes_inicio, $anio_inicio) = explode("/", $f_inicio); //Extrae fecha inicial
    $fecha_inicial                               = "$anio_inicio-$mes_inicio-$dia_inicio 00:00:00"; //Fecha inicial formato ingles
    list($dia_fin, $mes_fin, $anio_fin)          = explode("/", $f_final); //Extrae la fecha final
    $fecha_final                                 = "$anio_fin-$mes_fin-$dia_fin 23:59:59";

    $sWhere .= " and caja_chica.date_added_caja between '$fecha_inicial' and '$fecha_final' ";
}
$sWhere .= " order by caja_chica.id_caja";
$query = mysqli_query($conexion, "SELECT $campos FROM  $tables where $sWhere ");
// get the HTML
ob_start();
include dirname(__FILE__) . '/res/rep_caja_chica_html.php';
$content = ob_get_clean();

// convert to PDF
require_once "../../vendor/autoload.php";
use Spipu\Html2Pdf\Html2Pdf;
try
{
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 3);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    ob_end_clean();
    $html2pdf->Output('reporte_caja_chica.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}