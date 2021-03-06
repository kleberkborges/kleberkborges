<?php

include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Archivo de funciones PHP
include "../funciones.php";

//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Productos";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != null) ? $_REQUEST['action'] : '';
if ($action == 'ajax') {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $q            = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
    $id_categoria = intval($_REQUEST['categoria']);
    $aColumns     = array('codigo_producto', 'nombre_producto'); //Columnas de busqueda
    $sTable       = "productos";
    $sWhere       = "";
    if ($id_categoria > 0) {
        $sWhere .= "WHERE id_linea_producto = '" . $id_categoria . "' ";
    }
    if ($_GET['q'] != "") {
        $sWhere = "WHERE(";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';

    }

    $sWhere .= " order by nombre_producto asc";

    //Obtener moneda
    $simbolo_moneda = obtener_moneda();

    include 'pagination.php'; //include pagination file
    //pagination variables
    $page      = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page  = 10; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset    = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    $count_query = mysqli_query($conexion, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
    $row         = mysqli_fetch_array($count_query);
    $numrows     = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload      = '../html/productos.php';
    //main query to fetch the data
    $sql   = "SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
    $query = mysqli_query($conexion, $sql);
    //loop through fetched data
    if ($numrows > 0) {
        ?>
        <div class="table-responsive">
          <table class="table table-sm table-striped">
            <tr  class="info">
                <th>ID</th>
                <th>C??digo</th>
                <th>Producto</th>
                <th class='text-center'>Existencia</th>
                <th class='text-left'>Costo</th>
                <th class='text-left'>P. Venta</th>
                <th class='text-left'>P. Mayoreo</th>
                <th>Estado</th>
                <th>Agregado</th>
                <th class='text-right'>Acciones</th>

            </tr>
            <?php
while ($row = mysqli_fetch_array($query)) {
            $id_producto          = $row['id_producto'];
            $codigo_producto      = $row['codigo_producto'];
            $nombre_producto      = $row['nombre_producto'];
            $descripcion_producto = $row['descripcion_producto'];
            $linea_producto       = $row['id_linea_producto'];
            $med_producto         = $row['id_med_producto'];
            $id_proveedor         = $row['id_proveedor'];
            $inv_producto         = $row['inv_producto'];
            $impuesto_producto    = $row['iva_producto'];
            $unidad_medida        = $row['unidad_medida'];
            $costo_producto       = $row['costo_producto'];
            $utilidad_producto    = $row['utilidad_producto'];
            $precio_producto      = $row['valor1_producto'];
            $precio_mayoreo       = $row['valor2_producto'];
            $stock_producto       = $row['stock_producto'];
            $stock_min_producto   = $row['stock_min_producto'];
            $status_producto      = $row['estado_producto'];
            $date_added           = date('d/m/Y', strtotime($row['date_added']));
            $id_imp_producto      = $row['id_imp_producto'];
            if ($status_producto == 1) {
                $estado = "<span class='badge badge-success'>Activo</span>";
            } else {
                $estado = "<span class='badge badge-danger'>Inactivo</span>";
            }
            ?>

                <input type="hidden" value="<?php echo $codigo_producto; ?>" id="codigo_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $nombre_producto; ?>" id="nombre_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $descripcion_producto; ?>" id="descripcion_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $linea_producto; ?>" id="linea_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $id_proveedor; ?>" id="proveedor_producto<?php echo $id_producto; ?>">
                <!--<input type="hidden" value="<?php echo $med_producto; ?>" id="med_producto<?php echo $id_producto; ?>">-->
                <input type="hidden" value="<?php echo $inv_producto; ?>" id="inv_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $impuesto_producto; ?>" id="impuesto_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $unidad_medida; ?>" id="unidad_medida<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $stock_producto; ?>" id="stock_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $stock_min_producto; ?>" id="stock_min_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $status_producto; ?>" id="estado<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo formatear_moneda($simbolo_moneda, $costo_producto); ?>" id="costo_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $utilidad_producto; ?>" id="utilidad_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo formatear_moneda($simbolo_moneda, $precio_producto); ?>" id="precio_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo formatear_moneda($simbolo_moneda, $precio_mayoreo); ?>" id="precio_mayoreo<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $id_imp_producto; ?>" id="id_imp_producto<?php echo $id_producto; ?>">
                <input type="hidden" value="<?php echo $id_imp_producto; ?>" id="id_imp_producto<?php echo $id_producto; ?>">
                <tr>
                    <td><span class="badge badge-purple text-light"><?php echo $id_producto; ?></span></td>
                    
                    <td><?php echo $codigo_producto; ?></td>
                    <td ><?php echo $nombre_producto; ?></td>
                    <td class='text-center'><?php echo stock($stock_producto); ?></td>
                    <td><span class='pull-left'><?php echo $simbolo_moneda . '' . formatear_moneda($simbolo_moneda, $costo_producto); ?></span></td>
                    <td><span class='pull-left'><?php echo $simbolo_moneda . '' . formatear_moneda($simbolo_moneda, $precio_producto); ?></span></td>
                    <td><span class='pull-left'><?php echo $simbolo_moneda . '' . formatear_moneda($simbolo_moneda, $precio_mayoreo); ?></span></td>
                    <td><?php echo $estado; ?></td>
                    <td><?php echo $date_added; ?></td>
                    <td>

                        <div class="btn-group dropdown pull-right">
                            <button type="button" class="btn btn-warning rounded waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"> <i class='fa fa-cog'></i> <i class="caret"></i> </button>
                            <div class="dropdown-menu dropdown-menu-right">
<?php 
    if ($permisos_ver == 1) {
?>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarProducto" onclick="obtener_datos('<?php echo $id_producto; ?>');carga_img('<?php echo $id_producto; ?>');"><i class='fa fa-edit'></i> Editar</a>
<?php 
    } if ($permisos_editar == 1) {
?>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dataDeleteProducto" data-id="<?php echo $id_producto; ?>"><i class='fa fa-trash'></i> Borrar</a>
<?php 
    }
?>
                            </div>
                        </div>

                    </td>
                </tr>
<?php
    }
?>
       <tr>
        <td colspan=12><span class="pull-right">
<?php
    echo paginate($reload, $page, $total_pages, $adjacents);
?>
        </span></td>
        </tr>
    </table>
</div>
<?php
}
//Este else Fue agregado de Prueba de prodria Quitar
    else {
        ?>
    <div class="alert alert-warning alert-dismissible" role="alert" align="center">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Aviso!</strong> No hay Registro de Producto
  </div>
  <?php
}
// fin else
}
?>