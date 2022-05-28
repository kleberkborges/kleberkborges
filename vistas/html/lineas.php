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
$modulo = "Categorias";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$title     = "Categorias";
$pacientes = 1;
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
<?php if ($permisos_ver == 1) {
    ?>
				<div class="col-lg-12">
					<div class="portlet">
						<div class="portlet-heading bg-primary">
							<h3 class="portlet-title">Categorías</h3>
							<div class="portlet-widgets">
								<a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
								<span class="divider"></span>
								<a data-toggle="collapse" data-parent="#accordion3" href="#bg-tertiary"><i class="ion-minus-round"></i></a>
								<span class="divider"></span>
								<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="bg-tertiary" class="panel-collapse collapse show">
							<div class="portlet-body">

<?php
	if ($permisos_editar == 1) {
        include '../modal/registro_linea.php';
        include "../modal/editar_linea.php";
        include "../modal/eliminar_linea.php";
    }
?>

								<form class="form-horizontal" role="form" id="datos_cotizacion">
									<div class="form-group row">
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" class="form-control" id="q_categoria" placeholder="Buscar por Nombre" onkeyup='load_categorias(1);'>
												<span class="btn-group-toggle">
													<button type="button" class="btn btn-outline-info waves-effect waves-light" onclick='load(1);'>
													<span class="fa fa-search" ></span></button>
												</span>
											</div>
										</div>
										<div class="col-md-2">
											<span id="loader_categoria"></span>
										</div>
										<div class="col-md-2">
											<div class="btn-group pull-right">
												<button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#nuevaLinea"><i class="fa fa-plus"></i> Agregar</button>
											</div>

										</div>
										<div class="col-md-2">
											<div class="btn-group pull-right">
<?php
	if ($permisos_editar == 1) {
?>
												<div class="btn-group dropup">
													<button aria-expanded="false" class="btn btn-outline-default rounded waves-effect waves-light" data-toggle="dropdown" type="button">
														<i class='fa fa-file-text'></i> Reporte
														<span class="caret"></span>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="#" onclick="reporte_categorias();">
															<i class='fa fa-file-pdf-o'></i> PDF
														</a>
														<a class="dropdown-item" href="#" onclick="reporte_excel_categorias();">
															<i class='fa fa-file-excel-o'></i> Excel
														</a>
													</div>
												</div>
<?php }?>
											</div>
										</div>

									</div>
								</form>
								<div class="datos_ajax_delete_categoria"></div><!-- Datos ajax Final -->
								<div class='outer_div_categorias'></div><!-- Carga los datos ajax -->

							</div>
						</div>
					</div>
				</div>
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
	<!-- Todo el codigo js aqui -->
	<!-- ============================================================== -->
	<script type="text/javascript" src="../../js/lineas.js" async></script>
	<script type="text/javascript" src="../../js/VentanaCentrada.js" async></script>

	<script>
       	$(document).ready( function () {
        	$(".UpperCase").on("keypress", function () {
         	$input=$(this);
         		setTimeout(function () {
          			$input.val($input.val().toUpperCase());
    			},50);
        	})
	   	});
	   	function reporte_categorias () {
			VentanaCentrada('../pdf2/rep_categorias.php', 'Reporte', '', '800', '600', 'true');
		}

	</script>
	<?php require 'includes/footer_end.php'
?>

