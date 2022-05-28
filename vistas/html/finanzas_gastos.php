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
$modulo = "Compras";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$title     = "Compras";
require_once "../funciones.php";
?>

<?php require 'includes/header_start.php';?>

<?php require 'includes/header_end.php';?>
<!-- Begin page -->
<div id="wrapper" class="forced enlarged"> <!-- DESACTIVA EL MENU -->

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
								<h3 class="portlet-title">
									Finanzas de gastos
								</h3>
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
									<div class="row">
										<div class="col-lg-6 col-xl-3">
											
											<h5 class="text-dark  header-title m-t-0 m-b-30">Gastos del día</h5>
											<div class="widget-bg-color-icon card-box">
												<div class="bg-icon bg-icon-primary pull-left">
													<i class="ti-export text-primary"></i>
												</div>
												<div class="text-right">
													<h5 class="text-dark"><b class="counter text-info" id="gastos_dia"></b></h5>
													<p class="text-muted mb-0">Total Gastos</p>
												</div>
												<div class="clearfix"></div>
											</div>
											
										</div>
										<div class="col-lg-6 col-xl-3">
											
											<h5 class="text-dark  header-title m-t-0 m-b-30">Gastos de la semana</h5>
											<div class="widget-bg-color-icon card-box">
												<div class="bg-icon bg-icon-primary pull-left">
													<i class="ti-export text-primary"></i>
												</div>
												<div class="text-right">
													<h5 class="text-dark"><b class="counter text-info" id="gastos_semanales">0</b></h5>
													<p class="text-muted mb-0">Total Gastos</p>
												</div>
												<div class="clearfix"></div>
											</div>
											
										</div>
										<div class="col-lg-6 col-xl-3">
											
											<h5 class="text-dark  header-title m-t-0 m-b-30">Gastos del mes</h5>
											<div class="widget-bg-color-icon card-box">
												<div class="bg-icon bg-icon-primary pull-left">
													<i class="ti-export text-primary"></i>
												</div>
												<div class="text-right">
													<h5 class="text-dark"><b class="counter text-info" id="gastos_mensuales">0</b></h5>
													<p class="text-muted mb-0">Total Gastos</p>
												</div>
												<div class="clearfix"></div>
											</div>
											
										</div>
										<div class="col-lg-6 col-xl-3">
											
											<h5 class="text-dark  header-title m-t-0 m-b-30">Gastos del año</h5>
											<div class="widget-bg-color-icon card-box">
												<div class="bg-icon bg-icon-primary pull-left">
													<i class="ti-export text-primary"></i>
												</div>
												<div class="text-right">
													<h5 class="text-dark"><b class="counter text-info" id="gastos_anuales">0</b></h5>
													<p class="text-muted mb-0">Total Gastos</p>
												</div>
												<div class="clearfix"></div>
											</div>
											
										</div>
									</div>
									
									<!-- end row -->
									<div class="row">
										<div class="col-xl-12">
											
											<h5 class="text-dark  header-title m-t-0 m-b-30">Gastos del mes</h5>
										<div id="grafico_gastos_diarios"></div>
											
									</div>

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



<!-- ============================================================== -->
<!-- Codigos Para el Auto complete de proveedores -->
<script type="text/javascript" src="../../assets/js/plotly.js" defer></script>
<script type="text/javascript" src="../../js/finanzas_gastos.js" async></script>
<?php require 'includes/footer_end.php'
?>