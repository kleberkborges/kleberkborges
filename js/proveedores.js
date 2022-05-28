		$(document).ready(function() {
		    load_proveedores(1);
		});

		function load_proveedores(page) {
			var q = $("#q_proveedor").val();
		    $("#loader_proveedor").fadeIn('slow');
		    $.ajax({
		        url: '../ajax/buscar_proveedor.php?action=ajax&page=' + page + '&q=' + q,
		        beforeSend: function(objeto) {
					$('#loader_proveedor').html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(data) {
		            $(".outer_div_proveedores").html(data).fadeIn('slow');
					$('#loader_proveedor').html('');
		            $('[data-toggle="tooltip"]').tooltip({
		                html: true
		            });
		        }
		    })
		}
		$("#guardar_proveedor").submit(function(event) {
		    $('#guardar_datos').attr("disabled", true);
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/nuevo_proveedor.php",
		        data: parametros,
		        beforeSend: function(objeto) {
					$("#resultados_ajax_proveedores").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
					$("#resultados_ajax_proveedores").html(datos);
		            $('#guardar_datos').attr("disabled", false);
		            load_proveedores(1);
		            //resetea el formulario
		            $("#guardar_proveedor")[0].reset();
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
		$("#editar_proveedor").submit(function(event) {
		    $('#actualizar_datos').attr("disabled", true);
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/editar_proveedor.php",
		        data: parametros,
		        beforeSend: function(objeto) {
		            $("#resultados_ajax2").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
		            $("#resultados_ajax2").html(datos);
		            $('#actualizar_datos').attr("disabled", false);
		            load_proveedores(1);
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
		
		$('#dataDeleteProveedor').on('show.bs.modal', function(event) {
		    var button = $(event.relatedTarget) // Botón que activó el modal
		    var id = button.data('id') // Extraer la información de atributos de datos
		    var modal = $(this)
			modal.find('#id_proveedor').val(id);
		})
		$("#eliminarDatosProveedor").submit(function(event) {
			var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "../ajax/eliminar_proveedor.php",
		        data: parametros,
		        beforeSend: function(objeto) {
					$(".datos_ajax_delete_proveedor").html('<img src="../../img/ajax-loader.gif"> Cargando...');
		        },
		        success: function(datos) {
					$(".datos_ajax_delete_proveedor").html(datos);
					$('#dataDeleteProveedor').modal('hide');
		            load_proveedores(1);
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
		    var nombre_proveedor = $("#nombre_proveedor" + id).val();
		    var fiscal_proveedor = $("#fiscal_proveedor" + id).val();
		    var web_proveedor = $("#web_proveedor" + id).val();
		    var direccion_proveedor = $("#direccion_proveedor" + id).val();
		    var contacto_proveedor = $("#contacto_proveedor" + id).val();
		    var email_proveedor = $("#email_proveedor" + id).val();
		    var telefono_proveedor = $("#telefono_proveedor" + id).val();
		    var estado_proveedor = $("#estado_proveedor" + id).val();
		    $("#mod_nombre").val(nombre_proveedor);
		    $("#mod_fiscal").val(fiscal_proveedor);
		    $("#mod_web").val(web_proveedor);
		    $("#mod_direccion").val(direccion_proveedor);
		    $("#mod_contacto").val(contacto_proveedor);
		    $("#mod_email").val(email_proveedor);
		    $("#mod_telefono").val(telefono_proveedor);
		    $("#mod_estado").val(estado_proveedor);
		    $("#mod_id").val(id);
		}