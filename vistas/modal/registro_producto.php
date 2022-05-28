<?php
if (isset($conexion)) {
    ?>
	<div id="nuevoProducto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">×</button>
					<h4 class="modal-title"><i class='fa fa-edit'></i> Nuevo Producto</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="post" id="guardar_producto" name="guardar_producto">
						<div id="resultados_ajax_productos"></div>

						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a href="#categoriaYProveedor" data-toggle="tab" aria-expanded="true" class="nav-link active">
									Categoría y Proveedor
								</a>
							</li>
							<li class="nav-item">
								<a href="#info" data-toggle="tab" aria-expanded="false" class="nav-link">
									Datos Básicos
								</a>
							</li>
							<li class="nav-item">
								<a href="#precios" data-toggle="tab" aria-expanded="true" class="nav-link">
									Precios y Stock
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade active show" id="categoriaYProveedor">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="image" class="col-sm-2 control-label">Categoría</label>
											<div class="col-sm-10">
												<div class="btn-group pull-left">
													<button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#nuevaLinea"><i class="fa fa-plus"></i> Agregar</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="mt-5"></div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-sm-2 control-label">Proveedor</label>
											<div class="col-sm-10">
												<div class="btn-group pull-left">
													<button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#nuevoProveedor"><i class="fa fa-plus"></i> Agregar</button>
												</div>
											</div>
										</div>
									</div>
								</div>

							
							</div>
							<div class="tab-pane fade" id="info">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="codigo" class="control-label">Código:</label>
											<div id="cod_resultado"></div><!-- Carga los datos ajax del incremento de la fatura -->
										</div>

									</div>
									<div class="col-md-8">
										<div class="form-group">
											<label for="nombre" class="control-label">Nombre:</label>
											<input type="text" class="form-control UpperCase" id="nombre" name="nombre" autocomplete="off" required>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="descripcion" class="control-label">Descripción</label>
											<textarea class="form-control UpperCase"  id="descripcion" name="descripcion" maxlength="255" autocomplete="off"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="linea" class="control-label">Categoría:</label>
											<select class='form-control' name='linea' id='linea' required>
												<option value="">-- Selecciona --</option>
												<option value=""></option>
												<option value=""></option>
												<option value=""></option>
												<option value=""></option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="proveedor" class="control-label">Proveedor:</label>
											<select class='form-control' name='proveedor' id='proveedor' required>
												<option value="">-- Selecciona --</option>
												<option value=""></option>
												<option value=""></option>
												<option value=""></option>
												<option value=""></option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="estado" class="control-label">Estado:</label>
											<select class="form-control" id="estado" name="estado" required>
												<option value="">-- Selecciona --</option>
												<option value="1" selected>Activo</option>
												<option value="0">Inactivo</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="impuesto" class="control-label">Impuesto:</label>
											<select class="form-control" id="impuesto" name="impuesto" required>
												<option value="5" selected>5%</option>
												<option value="10">10%</option>
												<option value="0">Exento</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="unidad_medida" class="control-label">Unidad de medida:</label>
											<select class="form-control" id="unidad_medida" name="unidad_medida" required>
												<option selected>-- Selecciona --</option>
												<option value="Unidad" selected>Unidad</option>
												<option value="Kg">Kilogramo</option>
											</select>
										</div>
									</div>
								</div>

							</div>
							<div class="tab-pane fade" id="precios">

								<div class="row">
								<!--<div class="col-md-5">
										<div class="form-group">
											<label for="id_imp" class="control-label">Impuesto:</label>
											<select id = "id_imp" class = "form-control" name = "id_imp" required autocomplete="off">
												<option value="">-SELECCIONE-</option>
												<?php foreach ($impuesto as $i): ?>
													<option value="<?php echo $i->id_imp; ?>"><?php echo $i->nombre_imp; ?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>-->
									<div class="col-md-6">
										<div class="form-group">
											<label for="costo" class="control-label">Último Costo:</label>
											<input type="text" class="form-control" id="costo" name="costo" autocomplete="off" pattern="^[0-9.]{1,}$" title="Ingresa un número" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="utilidad" class="control-label">Utilidad %:</label>
											<input type="text" class="form-control" id="utilidad" name="utilidad" autocomplete="off" pattern="\d{1,4}" onkeyup="precio_venta();" >
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="precio" class="control-label">Precio Venta:</label>
											<input type="text" class="form-control" id="precio" name="precio" pattern="^[0-9.]{1,}$" autocomplete="off" title="Ingresa un número">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="preciom" class="control-label">Precio Mayoreo:</label>
											<input type="text" class="form-control" id="preciom" name="preciom" autocomplete="off" pattern="^[0-9.]{1,}$" title="Ingresa un número" >
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="inv" class="control-label">Maneja Inventario:</label>
											<select class="form-control" id="inv" name="inv" required>
												<option value="">- Selecciona -</option>
												<option value="0">Si</option>
												<option value="1">No</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="stock" class="control-label">Stock Inicial:</label>
											<input type="text" class="form-control" id="stock" name="stock" autocomplete="off" pattern="^[0-9]{1,}" title="Ingresa un número" value="0">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="minimo" class="control-label">Stock Mínimo:</label>
											<input type="text" class="form-control" id="minimo" name="minimo" autocomplete="off" pattern="^[0-9]{1,}$" title="Ingresa un número" value="1">
										</div>
									</div>

								</div>



							</div>


						</div>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary waves-effect waves-light" id="guardar_datos">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div><!-- /.modal -->
	<script type="text/javascript" src="../../js/jquery.js"></script>
	<script>
	$(document).ready(function(){
		const proveedorSelect = $('#proveedor');
		const categoriaSelect = $('#linea');

		proveedorSelect.focus(()=>{
			$.ajax({
				url: '../ajax/obtener_proveedores.php',
				success: (data)=>{
					let proveedores = JSON.parse(data);
					$('#proveedor option').remove();
					proveedorSelect.append("<option>-- Selecciona --</option>");
					proveedores.forEach(proveedor=>{
						proveedorSelect.append(`<option value='${proveedor.id_proveedor}'>${proveedor.nombre_proveedor}</option>`);
					});
				}
			});
		});

		categoriaSelect.focus(()=>{
			$.ajax({
				url: '../ajax/obtener_lineas.php',
				success: (data)=>{
					let lineas = JSON.parse(data);
					$('#linea option').remove();
					categoriaSelect.append("<option>-- Selecciona --</option>");
					lineas.forEach(linea => {
						categoriaSelect.append(`<option value='${linea.id_linea}'>${linea.nombre_linea}</option>`);
					});
				}
			});
		});
	
	})
	</script>
	<?php
}
?>