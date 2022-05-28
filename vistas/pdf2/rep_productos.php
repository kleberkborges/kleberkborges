<?php
    /** Conexion a la base de datos */
    require_once "../db.php";
    require_once "../php_conexion.php";

    //Inicia Control de Permisos
    include "../permisos.php";

    //Archivo de funciones PHP
    require_once "../funciones.php";

    /** Obtener variable por GET */
    if(isset($_GET['categoria']) && !empty($_GET['categoria'])){
        $id_categoria = $_GET['categoria'];
        $query = mysqli_query($conexion, "SELECT * FROM productos p,  lineas l WHERE p.id_linea_producto = '$id_categoria' ORDER BY p.id_producto");
    } else {
        $id_categoria = 0;
        $query = mysqli_query($conexion, "SELECT * FROM productos p, lineas l WHERE p.id_linea_producto = l.id_linea ORDER BY p.id_producto");
    }

    require_once "../../vendor/autoload.php";
    use Spipu\Html2Pdf\Html2Pdf;

    try{
        $html2pdf = new Html2Pdf('L', 'A4', 'es');

        ob_start();
        require_once __DIR__ . '/res/rep_productos_html.php';
        $html = ob_get_clean();

        $html2pdf->writeHTML($html);
        $html2pdf->output('reporte_productos.pdf');
    } catch (Exception $e){
        echo $e->getMessage();
        exit;
    }
?>