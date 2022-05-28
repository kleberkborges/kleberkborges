<?php
include '../ajax/is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
$id_producto = $_SESSION['id'];
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Inicia Control de Permisos
include "../permisos.php";
//Archivo de funciones PHP
require_once "../funciones.php";
$nombre_producto = get_row('productos', 'nombre_producto', 'id_producto', $id_producto);
//Ontengo variables pasadas por GET
$daterange = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['daterange'], ENT_QUOTES)));
if (!empty($_REQUEST['tipo']) && isset($_REQUEST['tipo'])) {
    $tipo      = intval($_REQUEST['tipo']);
}
$tables    = "historial_productos";
$campos    = "*";
$sWhere    = "id_producto='" . $id_producto . "'";
if (isset($tipo)) {
    if ($tipo > 0){
        $sWhere .= " and tipo_historial = '" . $tipo . "' ";
    }
}
if (!empty($daterange)) {
    list($f_inicio, $f_final)                    = explode(" - ", $daterange); //Extrae la fecha inicial y la fecha final en formato espa?ol
    list($dia_inicio, $mes_inicio, $anio_inicio) = explode("/", $f_inicio); //Extrae fecha inicial
    $fecha_inicial                               = "$anio_inicio-$mes_inicio-$dia_inicio 00:00:00"; //Fecha inicial formato ingles
    list($dia_fin, $mes_fin, $anio_fin)          = explode("/", $f_final); //Extrae la fecha final
    $fecha_final                                 = "$anio_fin-$mes_fin-$dia_fin 23:59:59";

    $sWhere .= " and fecha_historial between '$fecha_inicial' and '$fecha_final' ";
}
$sWhere .= " order by id_historial";
$query = mysqli_query($conexion, "SELECT $campos FROM  $tables where $sWhere ");
// get the HTML
ob_start();
include dirname(__FILE__) . '/res/rep_historial_html.php';
$content = ob_get_clean();

// convert to PDF
require_once "../../vendor/autoload.php";
use Spipu\Html2Pdf\Html2Pdf;
try
{
    $html2pdf = new Html2Pdf('L', 'A4', 'es');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    ob_end_clean();
    $html2pdf->Output('usuarios.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
