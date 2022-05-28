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

include 'pagination.php'; //include pagination file

$page      = 1;
$per_page  = 10; //how much records you want to show
$adjacents = 4; //gap between pages after number of adjacents
$offset    = ($page - 1) * $per_page;
$count_query = mysqli_query($conexion, "SELECT count(*) AS numrows FROM objetos");
$row         = mysqli_fetch_array($count_query);
$numrows     = $row['numrows'];
$total_pages = ceil($numrows / $per_page);
$reload      = '../html/productos.php';

$query = mysqli_query($conexion, "SELECT * FROM objetos LIMIT $offset, $per_page");
if($numrows > 0) {
?>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <tr  class="info">
                <th class="text-center">ID</th>
                <th>Nombre</th>
                <th class="text-right">Acciones</th>
            </tr>
<?php
    while ($row = mysqli_fetch_assoc($query)) {
        $id = $row['id'];
        $nombre = $row['nombre'];
?>
            <input type="hidden" value="<?=$id?>" id="id_objeto_servicio<?=$id?>">
            <input type="hidden" value="<?=$nombre?>" id="nombre_objeto_servicio<?=$id?>">
            <tr>
                <td class="text-center"><span class="badge badge-purple text-light"><?=$id?></span></td>
                <td ><?=$nombre?></td>
                <td>
                    <div class="btn-group dropdown pull-right">
                        <button type="button" class="btn btn-warning rounded waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"> <i class='fa fa-cog'></i> <i class="caret"></i> </button>
                        <div class="dropdown-menu dropdown-menu-right">
<?php 
    if ($permisos_ver == 1) {
?>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editObjetoServicio" onclick="obtener_datos('<?=$id?>');carga_img('<?=$id?>');"><i class='fa fa-edit'></i> Editar</a>
<?php 
    } if ($permisos_editar == 1) {
?>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteObjetoServicio" data-id="<?=$id?>"><i class='fa fa-trash'></i> Borrar</a>
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
