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
							<h3 class="portlet-title">Control de Inventario</h3>
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
    include "../modal/agregar_stock.php";
    include "../modal/eliminar_stock.php";
?>
		

								<form class="form-horizontal" role="form" id="datos_cotizacion">
									<div class="form-group row">
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" class="form-control" id="q_producto" placeholder="Código o Nombre" onkeyup='load_productos(1);'>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<select name='categoria' id='categoria' class="form-control" onchange="load_productos(1);">
													<option value="">-- Selecciona Categoría --</option>
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

									</div>
								</form>
                                <div class="datos_ajax_delete"></div><!-- Datos ajax Final -->
                                <div id="resultados_ajax"></div>
								<div class='outer_div_productos'></div><!-- Carga los datos ajax -->

                                <div class="row">
                                    <div class="table-responsive">
                                        <div class="panel panel-color panel-info">
                                            <div class="panel-body">
                                                <div class="col-md-12" align="center">
                                                    <div id="resultados_ajax"></div>
                                                    <div class="clearfix"></div>
                                                    <div id='outer_div'></div><!-- Carga los datos ajax-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
	<script type="text/javascript" src="../../js/control_inventario.js" defer></script>
	<script type="text/javascript" src="../../js/VentanaCentrada.js" defer></script>
	<!-- Codigos Para el Auto complete de Clientes -->
<script>
	$(function() {
		$("#nombre_producto").autocomplete({
			source: "../ajax/autocomplete/productos.php",
			minLength: 2,
			select: function(event, ui) {
				event.preventDefault();
				$('#id_producto').val(ui.item.id_producto);
				$('#nombre_producto').val(ui.item.nombre_producto);
				$('#outer_div').load("../ajax/carga_kardex.php?id_producto="+ui.item.id_producto);
				$.Notification.notify('custom','bottom center','EXITO!', 'SELECCIÓN DE PRODUCTO EXITOSA!')
			}
		});
	});

	$("#nombre_producto" ).on( "keydown", function( event ) {
		if (event.keyCode== $.ui.keyCode.LEFT || event.keyCode== $.ui.keyCode.RIGHT || event.keyCode== $.ui.keyCode.UP || event.keyCode== $.ui.keyCode.DOWN || event.keyCode== $.ui.keyCode.DELETE || event.keyCode== $.ui.keyCode.BACKSPACE )
		{
			$("#id_producto" ).val("");
		}
		if (event.keyCode==$.ui.keyCode.DELETE){
			$("#nombre_producto" ).val("");
			$("#id_producto" ).val("");
		}
	});
</script>
<!-- FIN -->
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
	<?php require 'includes/footer_end.php'
?>

