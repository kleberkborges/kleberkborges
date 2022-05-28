		$(document).ready(function() {
			load_categorias(1);
		});

		function load_categorias(page) {
		    var q = $("#q_categoria").val();
		    $("#loader_categoria").fadeIn('slow');
		    $.ajax({
		        url: '../ajax/buscar_linea.php?action=ajax&page=' + page + '&q=' + q,
		        beforeSend: function(objeto) {
					$('#loader_categoria').html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(data) {
		            $(".outer_div_categorias").html(data).fadeIn('slow');
					$('#loader_categoria').html('');
		            $('[data-toggle="tooltip"]').tooltip({
		                html: true
		            });
		        }
		    })
		}
		$("#guardar_linea").submit(function(event) {
		    $('#guardar_datos').attr("disabled", true);
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/nueva_linea.php",
		        data: parametros,
		        beforeSend: function(objeto) {
					$("#resultados_ajax_categorias").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
					$("#resultados_ajax_categorias").html(datos);
		            $('#guardar_datos').attr("disabled", false);
		            load_categorias(1);
		            //resetea el formulario
		            $("#guardar_linea")[0].reset();
		            $("#nombre").focus();
		            //desaparecer la alerta
		            window.setTimeout(function() {
		                $(".alert").fadeTo(200, 0).slideUp(200, function() {
		                    $(this).remove();
		                });
		            }, 2000);
		        }
		    });
		    event.preventDefault();
		})
		$("#editar_linea").submit(function(event) {
		    $('#actualizar_datos').attr("disabled", true);
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/editar_linea.php",
		        data: parametros,
		        beforeSend: function(objeto) {
		            $("#resultados_ajax2").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
		            $("#resultados_ajax2").html(datos);
		            $('#actualizar_datos').attr("disabled", false);
		            load_categorias(1);
		            //desaparecer la alerta
		            window.setTimeout(function() {
		                $(".alert").fadeTo(200, 0).slideUp(200, function() {
		                    $(this).remove();
		                });
		            }, 2000);
		        }
		    });
		    event.preventDefault();
		})

		$('#dataDeleteCategoria').on('show.bs.modal', function(event) {
		    var button = $(event.relatedTarget) // Botón que activó el modal
		    var id = button.data('id') // Extraer la información de atributos de datos
		    var modal = $(this)
		    modal.find('#id_linea').val(id)
		})
		$("#eliminarDatosCategoria").submit(function(event) {
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/eliminar_linea.php",
		        data: parametros,
		        beforeSend: function(objeto) {
					$(".datos_ajax_delete_categoria").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
		            $(".datos_ajax_delete_categoria").html(datos);
		            $('#dataDeleteCategoria').modal('hide');
		            load_categorias(1);
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
		    var nombre = $("#nombre" + id).val();
		    var descripcion = $("#descripcion" + id).val();
		    var estado = $("#estado" + id).val();
		    $("#mod_nombre").val(nombre);
		    $("#mod_descripcion").val(descripcion);
		    $("#mod_estado").val(estado);
		    $("#mod_id").val(id);
		}