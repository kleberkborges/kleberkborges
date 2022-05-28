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
$modulo = "Productos";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$count      = mysqli_query($conexion, "select MAX(codigo_producto) as codigo from productos");
$rw         = mysqli_fetch_array($count);
$product_id = $rw['codigo'] + 1;
//consulta para elegir el impuesto en la modal
$query    = $conexion->query("select * from impuestos");
$impuesto = array();
while ($r = $query->fetch_object()) {$impuesto[] = $r;}
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
<?php 
	if ($permisos_ver == 1) {
?>
				<!-- Productos -->
				<div class="col-lg-12">
					<div class="portlet">
						<div class="portlet-heading bg-primary">
							<h3 class="portlet-title">Productos</h3>
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
		include '../modal/registro_producto.php';
		include "../modal/editar_producto.php";
		include "../modal/eliminar_producto.php";
		include "../modal/registro_linea.php";
		include "../modal/registro_proveedor.php";
	}
?>

								<form class="form-horizontal" role="form" id="datos_cotizacion">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="input-group">
												<input type="text" class="form-control" id="q_producto" placeholder="Código o Nombre" onkeyup='load_productos(1);'>
											</div>
										</div>
										<div class="col-md-3">
											<div class="input-group">
												<select name='categoria' id='categoria' class="form-control" onchange="load_productos(1);">
													<option value="">Selecciona Categoria</option>
													<option value="">Todos</option>
<?php

    $query_categoria = mysqli_query($conexion, "select * from lineas order by nombre_linea");
    while ($rw = mysqli_fetch_array($query_categoria)) {
?>
													<option value="<?php echo $rw['id_linea']; ?>"><?php echo $rw['nombre_linea']; ?></option>
<?php
	}
?>
												</select>
												<span class="btn-group-toggle">
													<button class="btn btn-outline-info waves-effect waves-light" type="button" onclick='load_productos(1);'><i class='fa fa-search'></i></button>
												</span>
											</div>
										</div>
										<div class="col-md-2">
											<span id="loader_producto"></span>
										</div>

										<div class="col-md-2">
											<div class="btn-group pull-right">
												<button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#nuevoProducto"><i class="fa fa-plus"></i> Agregar</button>
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
														<a class="dropdown-item" href="#" onclick="reporte_productos();">
															<i class='fa fa-file-pdf-o'></i> PDF
														</a>
														<a class="dropdown-item" href="#" onclick="reporte_excel_productos();">
															<i class='fa fa-file-excel-o'></i> Excel
														</a>
													</div>
												</div>
<?php
	}
?>
											</div>
										</div>

									</div>
								</form>
								<div class="datos_ajax_delete"></div><!-- Datos ajax Final -->
								<div class='outer_div_productos'></div><!-- Carga los datos ajax -->

							</div>
						</div>
					</div>
				</div>

				<!-- Categorias -->

				<!-- Proveedores -->
				
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

<?php require 'includes/footer_start.php' ?>
	<!-- ============================================================== -->
	<!-- Todo el codigo js aqui-->
	<!-- ============================================================== -->
	<script type="text/javascript" src="../../js/VentanaCentrada.js" defer></script>
	<script type="text/javascript" src="../../js/formatear_moneda.js" defer></script>
	<script type="text/javascript" src="../../js/lineas.js" async></script>
	<script type="text/javascript" src="../../js/proveedores.js" async></script>
	<script type="text/javascript" src="../../js/productos.js" async></script>
	<script>
		
		function precio_venta(){
			var profit = $("#utilidad").val();
			var buying_price = $("#costo").val();

			var parametros = {"utilidad":profit,"costo":buying_price};
			$.ajax({
				dataType: "json",
				type:"POST",
				url:'../ajax/precio.php',
				data: parametros,
				success:function(data){
          			//$("#datos").html(data).fadeIn('slow');
          			$.each(data, function(index, element) {
          			var precio= element.precio;
          			$("#precio").val(precio);
          			});
				}
			})
		}
		function precio_venta_edit(){
			var profit = $("#mod_utilidad").val();
			var buying_price = $("#mod_costo").val();

			var parametros = {"mod_utilidad":profit,"mod_costo":buying_price};
			$.ajax({
				dataType: "json",
				type:"POST",
				url:'../ajax/precio.php',
				data: parametros,
				success:function(data){
          //$("#datos").html(data).fadeIn('slow');
          $.each(data, function(index, element) {
          	var mod_precio= element.mod_precio;
          	$("#mod_precio").val(mod_precio);
          });


      }
  })
		}

	</script>
	<script>
		$(document).ready( function () {
			$(".UpperCase").on("keypress", function () {
				$input=$(this);
				setTimeout(function () {
					$input.val($input.val().toUpperCase());
				},50);
			})
		})
	</script>
	<script>
		$(document).ready(()=>{
			formatear_moneda('costo');
			formatear_moneda('preciom');
			formatear_moneda('mod_costo');
			formatear_moneda('mod_preciom');
		});
	</script>
	<script>
		function reporte_excel_productos(){
			var q=$("#q_producto").val();
			window.location.replace("../excel/rep_productos.php?q="+q);
    //VentanaCentrada('../excel/rep_gastos.php?daterange='+daterange+"&employee_id="+employee_id,'Reporte','','500','25','true');+"&tipo="+tipo
		}
		function reporte_productos(){
			// var daterange=$("#range").val();
			var categoria=$("#categoria").val();
			VentanaCentrada('../pdf2/rep_productos.php?categoria=' + categoria,'Reporte','','800','600','true');
		}
		
</script>
<?php require 'includes/footer_end.php'
?>

