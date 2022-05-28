		$(document).ready(function() {
		    $("#cod_resultado").load("../ajax/incrementa_cod_prod.php");
			load_productos(1);
		});

		function load_productos(page) {
		    var q = $("#q_producto").val();
		    var categoria=$("#categoria").val();
		    $("#loader_producto").fadeIn('slow');
		    $.ajax({
		        url: '../ajax/buscar_productos.php?action=ajax&page=' + page + '&q=' + q + '&categoria=' + categoria,
		        beforeSend: function(objeto) {
					$('#loader_producto').html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(data) {
		            $(".outer_div_productos").html(data).fadeIn('slow');
					$('#loader_producto').html('');
		        }
		    })
		}
		$("#guardar_producto").submit(function(event) {
		    $('#guardar_datos').attr("disabled", true);
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/nuevo_producto.php",
		        data: parametros,
		        beforeSend: function(objeto) {
		            $("#resultados_ajax_productos").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
		            $("#resultados_ajax_productos").html(datos);
		            $('#guardar_datos').attr("disabled", false);
		            $("#cod_resultado").load("../ajax/incrementa_cod_prod.php");
		            load_productos(1);
		            //resetea el formulario
		            $("#guardar_producto")[0].reset();
		            //desaparecer la alerta
		            window.setTimeout(function() {
		                $(".alert").fadeTo(500, 0).slideUp(500, function() {
		                    $(this).remove();
		                });
		            }, 5000);
		        }
		    });
		    event.preventDefault();
		})
		$("#editar_producto").submit(function(event) {
		    $('#actualizar_datos').attr("disabled", true);
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/editar_producto.php",
		        data: parametros,
		        beforeSend: function(objeto) {
		            $("#resultados_ajax2").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
		            $("#resultados_ajax2").html(datos);
		            $('#actualizar_datos').attr("disabled", false);
		            load_productos(1);
		            //desaparecer la alerta
		            window.setTimeout(function() {
		                $(".alert").fadeTo(500, 0).slideUp(500, function() {
		                    $(this).remove();
		                });
		            }, 5000);
		        }
		    });
		    event.preventDefault();
		})
		$('#dataDeleteProducto').on('show.bs.modal', function(event) {
		    var button = $(event.relatedTarget) // Botón que activó el modal
		    var id = button.data('id') // Extraer la información de atributos de datos
		    var modal = $(this)
		    modal.find('#id_producto').val(id)
		})
		$("#eliminarDatosProducto").submit(function(event) {
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/eliminar_producto.php",
		        data: parametros,
		        beforeSend: function(objeto) {
		            $(".datos_ajax_delete").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
		            $(".datos_ajax_delete").html(datos);
		            $('#dataDeleteProducto').modal('hide');
		            load_productos(1);
		            //desaparecer la alerta
		            window.setTimeout(function() {
		                $(".alert").fadeTo(200, 0).slideUp(200, function() {
		                    $(this).remove();
		                });
		            }, 2000);
		        }
		    });
		    event.preventDefault();
		});

		function obtener_datos(id) {
		    var codigo_producto = $("#codigo_producto" + id).val();
		    var nombre_producto = $("#nombre_producto" + id).val();
		    var descripcion_producto = $("#descripcion_producto" + id).val();
		    var linea_producto = $("#linea_producto" + id).val();
		    var proveedor_producto = $("#proveedor_producto" + id).val();
		    //var med_producto = $("#med_producto" + id).val();
		    var inv_producto = $("#inv_producto" + id).val();
			var impuesto_producto = $("#impuesto_producto" + id).val();
			var unidad_medida = $("#unidad_medida" + id).val();
		    var costo_producto = $("#costo_producto" + id).val();
		    var utilidad_producto = $("#utilidad_producto" + id).val();
		    var precio_producto = $("#precio_producto" + id).val();
		    var precio_mayoreo = $("#precio_mayoreo" + id).val();
		    var stock_producto = $("#stock_producto" + id).val();
		    var stock_min_producto = $("#stock_min_producto" + id).val();
		    var id_imp_producto = $("#id_imp_producto" + id).val();
		    var estado = $("#estado" + id).val();
		    $("#mod_id").val(id);
		    $("#mod_codigo").val(codigo_producto);
		    $("#mod_nombre").val(nombre_producto);
		    $("#mod_descripcion").val(descripcion_producto);
		    $("#mod_linea").val(linea_producto);
		    $("#mod_proveedor").val(proveedor_producto);
		    //$("#mod_medida").val(med_producto);
		    $("#mod_inv").val(inv_producto);
			$("#mod_impuesto").val(impuesto_producto);
			$("#mod_unidad_medida").val(unidad_medida);
		    $("#mod_costo").val(costo_producto);
		    $("#mod_utilidad").val(utilidad_producto);
		    $("#mod_precio").val(precio_producto);
		    $("#mod_preciom").val(precio_mayoreo);
		    // $("#mod_precioe").val(precio_especial);
		    $("#mod_stock").val(stock_producto);
		    $("#mod_minimo").val(stock_min_producto);
		    $("#id_imp2").val(id_imp_producto);
		    $("#mod_estado").val(estado);
		}