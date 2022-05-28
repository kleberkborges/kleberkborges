<?php
    /** Conexion a la base de datos */
    require_once "../db.php";
    require_once "../php_conexion.php";

    //Inicia Control de Permisos
    include "../permisos.php";

    //Archivo de funciones PHP
    require_once "../funciones.php";

    $query = mysqli_query($conexion, "SELECT * FROM clientes");

    require_once "../../vendor/autoload.php";
    use Spipu\Html2Pdf\Html2Pdf;

    try{

        $html2pdf = new Html2Pdf('L', 'A4', 'es');

        ob_start();
        require_once __DIR__ . '/res/rep_clientes_html.php';
        $html = ob_get_clean();

        $html2pdf->writeHTML($html);
        $html2pdf->output('reporte_clientes.pdf');
    } catch (Exception $e) {
        echo $e;
        exit;
    }

?>