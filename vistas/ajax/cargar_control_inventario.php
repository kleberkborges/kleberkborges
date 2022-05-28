<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*Inicia validacion del lado del servidor*/

/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Archivo de funciones PHP
require_once "../funciones.php";
//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Productos";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
$id_producto    = intval($_GET['id']);
$_SESSION['id'] = $id_producto;
$sql            = mysqli_query($conexion, "SELECT * FROM productos WHERE id_producto='$id_producto'");
$rw             = mysqli_fetch_array($sql);
$nombre         = $rw['nombre_producto'];
$stock          = $rw['stock_producto'];
?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-color panel-info">
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="form-group row justify-content-center mx-0 my-3">
						<div class="col-xs-4">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control daterange pull-right" value="<?php echo "01" . date('/m/Y') . ' - ' . date('d/m/Y'); ?>" id="range" readonly>
								<span class="btn-group-toggle">
									<button class="btn btn-outline-info waves-effect waves-light" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
								</span>

							</div><!-- /input-group -->
						</div>
						<div class="col-xs-4">
							<div id="loader" class="text-left"></div>
						</div>
						<div class="col-xs-4">
							<div class="btn-group dropdown">
								<button type="button" class="btn btn-default rounded dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"> <i class='fa fa-print'></i> <i class="caret"></i> Imprimir</button>
								<div class="dropdown-menu dropdown-menu-right">
									<?php if ($permisos_editar == 1) {?>
									<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarLinea" onclick="reporte();"><i class='fa fa-edit'></i> Imprimir</a>
									<!--<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarLinea" onclick="reporte_excel();"><i class='fa fa-edit'></i> Excel</a>-->
									<?php }?>
								</div>
							</div>
						</div>

					</div>
				</form>
				<div class="col-md-12" align="center">
					<div id="resultados_ajax"></div>
					<div class="clearfix"></div>
					<table>
						<tr>
							<th>Nombre de Producto: <b><?php echo $nombre; ?></b></th>
						</tr>
					</table>
					<div class='outer_div'></div><!-- Carga los datos ajax -->
				</div>
			</div>
		</div>
	</div>
</div>

</div>
<script>
	$(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
    });
	$(function() {
		load(1);

//Date range picker
$('.daterange').daterangepicker({
	buttonClasses: ['btn', 'btn-sm'],
	applyClass: 'btn-success',
	cancelClass: 'btn-default',
	locale: {
		format: "DD/MM/YYYY",
		separator: " - ",
		applyLabel: "Aplicar",
		cancelLabel: "Cancelar",
		fromLabel: "Desde",
		toLabel: "Hasta",
		customRangeLabel: "Custom",
		daysOfWeek: [
		"Do",
		"Lu",
		"Ma",
		"Mi",
		"Ju",
		"Vi",
		"Sa"
		],
		monthNames: [
		"Enero",
		"Febrero",
		"Marzo",
		"Abril",
		"Mayo",
		"Junio",
		"Julio",
		"Agosto",
		"Septiembre",
		"Octubre",
		"Noviembre",
		"Diciembre"
		],
		firstDay: 1
	},
	opens: "right"

});
});
	function load(page){
		var q= $("#q").val();
		var tipo=$("#tipo").val();
		var trans=$("#trans").val();
		var range=$("#range").val();
		var parametros = {"action":"ajax","page":page,'tipo':tipo,'trans':trans,'range':range,'q':q,};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'../ajax/kardex_producto.php',
			data: parametros,
			beforeSend: function(objeto){
				$("#loader").html("<img src='../../img/ajax-loader.gif'>");
			},
			success:function(data){
				$(".outer_div").html(data).fadeIn('slow');
				$("#loader").html("");
			}
		})
	}
</script>
<script>
  function reporte(){
    var daterange=$("#range").val();
    var employee_id=$("#employee_id").val();

    VentanaCentrada('../pdf2/rep_kardex.php?daterange='+daterange,'Reporte','','800','600','true');
  }
  </script>