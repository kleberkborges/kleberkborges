<?php
    /** Conexion a la base de datos */
    require_once "../db.php";
    require_once "../php_conexion.php";

    //Inicia Control de Permisos
    include "../permisos.php";

    //Archivo de funciones PHP
    require_once "../funciones.php";

    if(isset($_GET['q']) && !empty($_GET['q'])){
        $q = $_GET['q'];
        $query = mysqli_query($conexion, "SELECT * FROM  proveedores WHERE (nombre_proveedor LIKE '%q%' OR fiscal_proveedor LIKE '%$q%') ORDER BY id_proveedor ASC");
    } else {
        $q = '';
        $query = mysqli_query($conexion, "SELECT * FROM proveedores");
    }
    require_once "../../vendor/autoload.php";
    use Spipu\Html2Pdf\Html2Pdf;

    try{

        $html2pdf = new Html2Pdf('L', 'A4', 'es');

        ob_start();
        require_once __DIR__ . '/res/rep_proveedores_html.php';
        $html = ob_get_clean();

        $html2pdf->writeHTML($html);
        $html2pdf->output('reporte_proveedores.pdf');
    } catch (Exception $e) {
        echo $e;
        exit;
    }
?>