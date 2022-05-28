<?php
if (isset($conexion)) {
    ?>
	<div id="registroObjetoServicio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class='fa fa-edit'></i> Nuevo Objeto de Servicio</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="post" id="nuevo_objeto_servicio" name="nuevo_objeto_servicio">
						<div id="resultados_ajax_objeto_servicio"></div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="nombre" class="control-label">Nombre:</label>
									<input type="text" class="form-control UpperCase" id="nombre" name="nombre"  autocomplete="off" required>
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
	<?php
}
?>