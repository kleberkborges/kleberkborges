$(document).ready(function() {
    $("#resultados").load("../ajax/agregar_tmp_cot.php");
    $("#f_resultado").load("../ajax/incrementa_fact_cot.php");
    $("#datos_factura").load();
    $("#barcode").focus();
    load(1);
});

function load(page) {
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: '../ajax/productos_modal_ventas.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function(objeto) {
            $('#loader').html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    })
}

function agregar(id) {
    var regex = new RegExp('^[0-9. ]{2,}$', 'g');
    var precio_venta = document.getElementById('precio_venta_' + id).value;
    var cantidad = document.getElementById('cantidad_' + id).value;
    //Inicia validacion
    if (isNaN(cantidad)) {
        $.Notification.notify('error', 'bottom center', 'NOTIFICACIÓN', 'LA CANTIDAD NO ES UN NUMERO, INTENTAR DE NUEVO')
        document.getElementById('cantidad_' + id).focus();
        return false;
    }
    if (!(regex.test(precio_venta))) {
        $.Notification.notify('error', 'bottom center', 'NOTIFICACIÓN', 'EL PRECIO NO ES UN NUMERO, INTENTAR DE NUEVO')
        document.getElementById('precio_venta_' + id).focus();
        return false;
    }
    //Fin validacion
    $.ajax({
        type: "POST",
        url: "../ajax/agregar_tmp_modalcot.php",
        data: "id=" + id + "&precio_venta=" + precio_venta + "&cantidad=" + cantidad + "&operacion=" + 2,
        beforeSend: function(objeto) {
            $("#resultados").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#resultados").html(datos);
        }
    });
}
//CONTROLA EL FORMULARIO DEL CODIGO DE BARRA
$("#barcode_form").submit(function(event) {
    var id = $("#barcode").val();
    var cantidad = $("#barcode_qty").val();
    var id_sucursal = 0;
    //Inicia validacion
    if (isNaN(cantidad)) {
        $.Notification.notify('error', 'bottom center', 'NOTIFICACIÓN', 'LA CANTIDAD NO ES UN NUMERO, INTENTAR DE NUEVO')
        $("#barcode_qty").focus();
        return false;
    }
    //Fin validacion
    parametros = {
        'operacion': 1,
        'id': id,
        'id_sucursal': id_sucursal,
        'cantidad': cantidad
    };
    $.ajax({
        type: "POST",
        url: "../ajax/agregar_tmp_cot.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#resultados").html(datos);
            $("#id").val("");
            $("#id").focus();
            $("#barcode").val("");
            $("#f_resultado").load("../ajax/incrementa_fact_cot.php"); //Actualizamos el numero de Factura
        }
    });
    event.preventDefault();
})

function eliminar(id) {
    $.ajax({
        type: "GET",
        url: "../ajax/agregar_tmp_cot.php",
        data: "id=" + id,
        beforeSend: function(objeto) {
            $("#resultados").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#resultados").html(datos);
        }
    });
}
$("#datos_factura").submit(function(event) {
    $('#guardar_factura').attr("disabled", true);
    var id_cliente = $("#id_cliente").val();
    if (id_cliente == "") {
        $.Notification.notify('warning', 'bottom center', 'NOTIFICACIÓN', 'DEBE SELECCIONAR UN CLIENTE VALIDO')
        $("#nombre_cliente").focus();
        $('#guardar_factura').attr("disabled", false);
        return false;
    }
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "../ajax/guardar_cotizacion.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajaxf").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#resultados_ajaxf").html(datos);
            $('#guardar_factura').attr("disabled", false);
            //resetea el formulario
            //$('#modal_vuelto').modal('show');
            $("#datos_factura")[0].reset(); //Recet al formilario de el cliente
            $("#barcode_form")[0].reset(); // Recet al formulario de la fatura
            $("#resultados").load("../ajax/agregar_tmp_cot.php"); // carga los datos nuevamente
            $("#f_resultado").load("../ajax/incrementa_fact_cot.php"); // carga la caja de incrementar la factura
            $("#barcode").focus();
            load(1);
            //desaparecer la alerta
            $(".alert-success").delay(400).show(10, function() {
                $(this).delay(2000).hide(10, function() {
                    $(this).remove();
                });
            }); // /.alert
        }
    });
    event.preventDefault();
})
$("#guardar_cliente").submit(function(event) {
    $('#guardar_datos').attr("disabled", true);
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "../ajax/nuevo_cliente.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajax").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#resultados_ajax").html(datos);
            $('#guardar_datos').attr("disabled", false);
            //resetea el formulario
            $("#guardar_cliente")[0].reset();
            //desaparecer la alerta
            $(".alert-success").delay(400).show(10, function() {
                $(this).delay(2000).hide(10, function() {
                    $(this).remove();
                });
            }); // /.alert
            load(1);
        }
    });
    event.preventDefault();
})
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
            //resetea el formulario
            $("#guardar_producto")[0].reset();
            //desaparecer la alerta
            $(".alert-success").delay(400).show(10, function() {
                $(this).delay(2000).hide(10, function() {
                    $(this).remove();
                });
            }); // /.alert
            load(1);
        }
    });
    event.preventDefault();
})

function imprimir_factura(user_id) {
    VentanaCentrada('../pdf/documentos/corte_caja.php?user_id=' + user_id, 'Corte', '', '724', '568', 'true');
}