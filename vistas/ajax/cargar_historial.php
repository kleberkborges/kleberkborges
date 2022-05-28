<?php
include "is_logged.php"; //Archivo comprueba si el usuario esta logueado
$id_producto = 34;
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";

//Inicia Control de Permisos
include "../permisos.php";
//Archivo de funciones PHP
require_once "../funciones.php";
$user_id = $_SESSION['id_users'];

$action  = (isset($_REQUEST['action']) && $_REQUEST['action'] != null) ? $_REQUEST['action'] : '';
if (!empty($_GET['id'])) {
    $id_producto = $_GET['id'];
    $_SESSION['id'] = $id_producto;
    if (!empty($_REQUEST['tipo'])) {
        $tipo = intval($_REQUEST['tipo']);
        $where = "tipo_historial = $tipo";
        $count_query = mysqli_query($conexion, "SELECT count(*) AS numrows FROM historial_productos WHERE $where");
    } else {
        $count_query = mysqli_query($conexion, "SELECT count(*) AS numrows FROM historial_productos");
    }

    require_once 'pagination.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;

    $per_page  = 100; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset    = ($page - 1) * $per_page;

    if ($row = mysqli_fetch_array($count_query)) {$numrows = $row['numrows'];} else {echo mysqli_error($conexion);}
    $total_pages = ceil($numrows / $per_page);
    $reload      = '../ver_historial.php';

    if (!empty($_REQUEST['tipo'])) {
        $query = mysqli_query($conexion, "SELECT * FROM historial_productos WHERE id_producto = '$id_producto' AND tipo_historial = '$tipo' LIMIT $offset, $per_page");
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM historial_productos WHERE id_producto = '$id_producto' LIMIT $offset, $per_page");
    }
    $nombre_producto_query = mysqli_query($conexion, "SELECT nombre_producto FROM productos WHERE id_producto = '$id_producto'");
    $nombre_producto = mysqli_fetch_row($nombre_producto_query);
    if ($numrows > 0) {
?>
    <div class="row">
        <div class="table-responsive">
            <div class="panel panel-color panel-info">
                <div class="panel-body">
                    <b><p class="text-center">Producto: <?=$nombre_producto[0]?></p></b>
                    <form class="form-horizontal" role="form" id="datos_cotizacion">
                        <div class="form-group row justify-content-center mx-0 my-3">
                            <div class="col-xs-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control daterange pull-right" value="<?php echo "01" . date('/m/Y') . ' - ' . date('d/m/Y'); ?>" id="range" readonly>

                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group">
                                    <select class='form-control' id="tipo" name="tipo">
                                        <option value="">Selecciona Tipo</option>
                                        <option value="">Todos</option>
                                        <option value="1">Entradas</option>
                                        <option value="2">Salidas</option>
                                    </select>
                                    <span class="btn-group-toggle">
                                        <button class="btn btn-outline-info waves-effect waves-light" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                                    </span>
                                </div>
                            </div>

                            <div class="col-xs-3">
                                <div id="loader" class="text-left"></div>
                            </div>

                            <div class="col-xs-1">
                                <div class="btn-group pull-center">
                                    <button type="button"  onclick="reporte();" class="btn btn-default rounded waves-effect waves-light" title="Imprimir"><i class='fa fa-print'></i> Imprimir</button>
                                </div>
                            </div>

                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
            <table class="table table-sm table table-condensed table-hover table-striped ">
                <tr>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Hora</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Referencia</th>
                    <th class="text-center">Tipo</th>
                    <th class='text-center' width="5%">Total</th>
                </tr>

<?php
        $finales = 0;
        while ($row = mysqli_fetch_assoc($query)) {
            if($row['tipo_historial'] == 1) {
                $tipo = "<label class='badge badge-success'>Entradas</label>";
            } else {
                $tipo = "<label class='badge badge-danger'>Salida</label>";
            }
            $id_user = $row['id_users'];
            $query_two = mysqli_query($conexion, "SELECT usuario_users FROM users WHERE id_users = '$id_user'");
            $row_two = mysqli_fetch_assoc($query_two);
            $usuario = $row_two['usuario_users'];
?>
                <tr>
                    <td class="text-center"><?=date('d/m/Y', strtotime($row['fecha_historial']))?></td>
                    <td class="text-center"><?=date('H:i:s', strtotime($row['fecha_historial']))?></td>
                    <td><?=$usuario . ' ' . $row['nota_historial']?></td>
                    <td><?=$row['referencia_historial']?></td>
                    <td class="text-center"><?=$tipo?></td>
                    <td class="text-center"><?=$row['cantidad_historial']?></td>
                </tr>

<?php
        }
?>
    </table>
</div>

<div class="box-footer clearfix" align="right">

<?php
    $inicios = $offset + 1;
    $finales += $inicios - 1;
    echo "Mostrando $inicios al $finales de $numrows registros";
    echo paginate($reload, $page, $total_pages, $adjacents);
?>

</div>
<?php
    }
}
?>