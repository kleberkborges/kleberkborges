<?php
session_start();
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
    header("location: ../../login.php");
    exit;
}
require_once "includes/session_time.php";

/* Connect To Database*/
require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Inicia Control de Permisos



include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Inicio";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$title  = "Inicio";
$Inicio = 1;
//Archivo de funciones PHP
require_once "../funciones.php";
?>
<?php require 'includes/header_start.php';?>

<?php require 'includes/header_end.php';?>

<!-- Begin page -->
<div id="wrapper">

  <?php require 'includes/menu.php';?>

  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <div class="content-page">
    <!-- Start content -->
    <div class="content">
      <div class="container">
        <?php if ($permisos_ver == 1) {?>
          <div class="col-lg-12">

            <div class="portlet">
                <div class="portlet-heading bg-primary">
                    <h3 class="portlet-title">objetos de servicio</h3>
                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#bg-primary"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="bg-primary" class="panel-collapse collapse show">
                    <div class="portlet-body">

<?php
if ($permisos_editar == 1) {
    include '../modal/registro_objeto_servicio.php';
}
?>
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#registroObjetoServicio"><i class="fa fa-plus"></i> Agregar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="datos_ajax_delete mt-2"></div><!-- Datos ajax Final -->
                        <div class='outer_div_objetos_servicios mt-2'></div><!-- Carga los datos ajax -->

                    </div>
                </div>
            </div>

          </div>
          <!-- end row -->



          <?php
} else {
    ?>
          <section class="content">
            <div class="alert alert-danger" align="center">
              <h3>Acceso denegado! </h3>
              <p>No cuentas con los permisos necesario para acceder a este módulo.</p>
            </div>
          </section>
          <?php
}
?>
      </div>
      <!-- end container -->
    </div>
    <!-- end content -->

    <?php require 'includes/pie.php';?>

  </div>
  <!-- ============================================================== -->
  <!-- End Right content here -->
  <!-- ============================================================== -->

</div>
<!-- END wrapper -->


<?php require 'includes/footer_start.php'
?>
<!-- ============================================================== -->
<!-- Todo el codigo js aqui-->
<!-- ============================================================== -->
<script type="text/javascript" src="../../js/objetos_servicios.js" async></script>

<?php require 'includes/footer_end.php'
?>