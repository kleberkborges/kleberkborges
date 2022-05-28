<?php
if (isset($conexion)) {
    ?>
	<div id="nuevoServicio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">×</button>
					<h4 class="modal-title"><i class='fa fa-edit'></i> Nuevo Servicio</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="post" id="guardar_servicio" name="guardar_servicio">
						<div id="resultados_ajax_servicios"></div>

						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a href="#general" data-toggle="tab" aria-expanded="true" class="nav-link active">
									General
								</a>
							</li>
							<li class="nav-item">
								<a href="#cliente" data-toggle="tab" aria-expanded="false" class="nav-link">
									Cliente
								</a>
							</li>
							<li class="nav-item">
								<a href="#precios" data-toggle="tab" aria-expanded="true" class="nav-link">
									Precios y Stock
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade active show" id="general">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-sm-2 control-label">Estado</label>
											<div class="col-md-12">
												<div class="btn-group pull-left">
													<button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#registroEstadoServicio"><i class="fa fa-plus"></i> Agregar</button>
												</div>
											</div>
										</div>
									</div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Grupo</label>
                                            <div class="col-md-12">
                                                <div class="btn-group pull-left">
                                                    <button type="button" class="btn btn-success rounded waves-effect waves-light" data-toggle="modal" data-target="#registroGrupoServicio"><i class="fa fa-user-plus"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
                                <div class="my-3"></div>
                                <div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="emision" class="col-sm-2 control-label">Emision:</label>
											<div class="col-sm-12">
												<input type="date" name="emision" id="emision" class="rounded form-control">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-sm-2 control-label">Previsión:</label>
											<div class="col-sm-12">
												<input type="date" name="prevision" id="prevision" class="rounded form-control">
											</div>
										</div>
									</div>
								</div>
                                <div class="my-3"></div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="estados" class="col-sm-2 control-label">Estado:</label>
											<div class="col-sm-12">
												<select class='form-control' name='estados' id='estados' required>
													<option value="">-- Selecciona --</option>
													<option value=""></option>
													<option value=""></option>
													<option value=""></option>
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="grupos" class="col-sm-2 control-label">Grupo:</label>
											<div class="col-sm-12">
												<select class='form-control' name='grupos' id='grupos' required>
													<option value="">-- Selecciona --</option>
													<option value=""></option>
													<option value=""></option>
													<option value=""></option>
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="cliente">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="nombre_cliente" class="control-label">Cliente:</label>

											<select class="form-control" id="nombre_cliente" name="nombre_cliente" required title="Ingresa el cliente">
												<option value="">-- Seleccione cliente --</option>
<?php
	$query = mysqli_query($conexion, "SELECT id_cliente, nombre_cliente FROM clientes");
	while($row = mysqli_fetch_assoc($query)) {
?>
													<option value="<?=$row['id_cliente']?>"><?=$row['nombre_cliente']?></option>		
<?php
	}
?>
											</select>
										</div>

									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="telefono_cliente" class="control-label">Teléfono:</label>
											<input type="text" class="form-control UpperCase" id="telefono_cliente" name="telefono_cliente" autocomplete="off" readonly>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="contacto_cliente" class="control-label">Contacto:</label>
											<input class="form-control"  id="contacto_cliente" name="contacto_cliente" autocomplete="off" readonly>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="objeto_servicio" class="control-label">Objeto:</label>
											<select class='form-control' name='objeto_servicio' id='objeto_servicio' required>
												<option value="">-- Selecciona objeto --</option>
												<option value=""></option>
												<option value=""></option>
												<option value=""></option>
												<option value=""></option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
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
		const estadoSelect = $('#estados');
		const grupoSelect = $('#grupos');
		const objetoSelect = $('#objeto_servicio');
		const cliente = $('#nombre_cliente');

		estadoSelect.focus(()=>{
			$.ajax({
				method: 'GET',
				url: '../ajax/obtener_estados_servicios.php',
				success: data => {
					let estados = JSON.parse(data);
					$('#estados option').remove();
					estadoSelect.append("<option>-- Selecciona --</option>");
					estados.forEach(estado=>{
						estadoSelect.append(`<option value='${estado.id}'>${estado.nombre}</option>`);
					});
				}
			});
		});

		grupoSelect.focus(()=>{
			$.ajax({
				method: 'GET',
				url: '../ajax/obtener_grupos_servicios.php',
				success: data => {
					let grupos = JSON.parse(data);
					$('#grupos option').remove();
					grupoSelect.append("<option>-- Selecciona --</option>");
					grupos.forEach(grupo => {
						grupoSelect.append(`<option value='${grupo.id}'>${grupo.nombre}</option>`);
					});
				}
			});
		});
		objetoSelect.focus(()=>{
			$.ajax({
				method: 'GET',
				url: '../ajax/obtener_objetos_servicios.php',
				success: data => {
					let objetos = JSON.parse(data);
					$('#objeto_servicio option').remove();
					objetoSelect.append("<option>-- Selecciona --</option>");
					objetos.forEach(objeto => {
						objetoSelect.append(`<option value='${objeto.id}'>${objeto.nombre}</option>`);
					});
				}
			});
		});

		cliente.change(() => {
			let id = cliente.val();
			let data = {
				id: id
			}
			$.ajax({
				url: '../ajax/obtener_datos_cliente.php',
				method: 'POST',
				data: data,
				success: data => {
					let cliente = JSON.parse(data);
					$('#telefono_cliente').val(cliente.telefono_cliente);
					$('#contacto_cliente').val(cliente.email_cliente)
				}
			});
		});
		
	
	})
	</script>
	<?php
}
?>