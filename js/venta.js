$(document).ready(function() {
    $("#resultados").load("../ajax/agregar_tmp.php");
    //$("#f_resultado").load("../ajax/incrementa_factura.php");
    $("#resultados2").load("../ajax/carga_caja.php");
    $("#resultados3").load("../ajax/carga_resibido.php");
    // $("#resultados4").load("../ajax/tipo_doc.php");
    $("#resultados5").load("../ajax/carga_num_trans.php");
    $("#datos_factura").load();
    $("#barcode").focus();

    setTimeout(() => {
        formatear_moneda('resibido');
    }, 300);

    load(1);
});

function showDiv(select) {
    if (select.value == 2) {
        $('#resultados3').load('../ajax/carga_cheque_venta.php');
        $("#credito_display").empty();
        setTimeout(() => {
            formatear_moneda('resibido');
        }, 300);
    }
    else if (select.value == 4) {
        $("#resultados3").load("../ajax/carga_credito.php");
        $("#credito_display").load("../ajax/carga_credito_display.php");
        setTimeout(() => {
            formatear_moneda('resibido');
        }, 300);
    } else if (select.value == 3) {
        $('#resultados3').load('../ajax/carga_tarjeta.php');
        $("#credito_display").empty();
    } else {
        $("#resultados3").load("../ajax/carga_resibido.php");
        $("#credito_display").empty();
        setTimeout(() => {
            formatear_moneda('resibido');
        }, 300);
    }
}
function comprobar(select) {
    var rnc = $("#rnc").val();
    if (select.value == 1 && rnc == '') {
        $.Notification.notify('warning', 'bottom center', 'NOTIFICACIÓN', 'AL CLIENTE SELECCIONADO NO SE LE PUEDE IMPRIR LA FACTURA, NO TIENE RUC/DEDULA REGISTRADO')
        $("#resultados4").load("../ajax/tipo_doc.php");
    }
}
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
    var regex = new RegExp('^[0-9.]{2,}$', 'g');
    var precio_venta = document.getElementById('precio_venta_' + id).value;
    var cantidad = document.getElementById('cantidad_' + id).value;
    //Inicia validacion
    if (isNaN(cantidad)) {
        $.Notification.notify('error','bottom center','NOTIFICACIÓN', 'LA CANTIDAD NO ES UN NUMERO, INTENTAR DE NUEVO')
        document.getElementById('cantidad_' + id).focus();
        return false;
    }
    if (!(regex.test(precio_venta))) {
        $.Notification.notify('error','bottom center','NOTIFICACIÓN', 'EL PRECIO NO ES UN NUMERO, INTENTAR DE NUEVO')
        document.getElementById('precio_venta_' + id).focus();
        return false;
    }
    //Fin validacion
    $.ajax({
        type: "POST",
        url: "../ajax/agregar_tmp_modalventas.php",
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
        $.Notification.notify('error','bottom center','NOTIFICACIÓN', 'LA CANTIDAD NO ES UN NUMERO, INTENTAR DE NUEVO')
        $("#barcode_qty").focus();
        return false;
    }
    //Fin validacion
    parametros = {
        'operacion':1,
        'id': id,
        'id_sucursal': id_sucursal,
        'cantidad': cantidad
    };
    $.ajax({
        type: "POST",
        url: "../ajax/agregar_tmp.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#resultados").html(datos);
            $("#id").val("");
            $("#id").focus();
            $("#barcode").val("");
            $("#f_resultado").load("../ajax/incrementa_factura.php"); //Actualizamos el numero de Factura
        }
    });
    event.preventDefault();
})

function eliminar(id) {
    $.ajax({
        type: "GET",
        url: "../ajax/agregar_tmp.php",
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
    var resibido = $("#resibido").val();
    var condiciones = $('#condiciones').val();
    if (isNaN(resibido) && condiciones != 3) {
        $.Notification.notify('error','bottom center','NOTIFICACIÓN', 'EL DATO NO ES VÁLIDO, INTENTAR DE NUEVO')
        $("#resibido").focus();
        return false;
    }
    if (id_cliente == "") {
        $.Notification.notify('warning','bottom center','NOTIFICACIÓN', 'DEBE SELECCIONAR UN CLIENTE VALIDO')
        $("#nombre_cliente").focus();
        $('#guardar_factura').attr("disabled", false);
        return false;
    }
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "../ajax/nueva_venta.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajaxf").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function(datos) {
            $("#datos_factura")[0].reset(); //Reset al formulario de el cliente
            $("#barcode_form")[0].reset(); // Reset al formulario de la fatura
            $("#resultados_ajaxf").html(datos);
            $('#guardar_factura').attr("disabled", false);
            //resetea el formulario
            //$('#modal_vuelto').modal('show');
            $("#resultados").load("../ajax/agregar_tmp.php"); // carga los datos nuevamente
            $("#f_resultado").load("../ajax/incrementa_factura.php"); // carga la caja de incrementar la factura
            $("#resultados2").load("../ajax/carga_caja.php"); // carga la caja total del dia
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
$('#dataDelete').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Botón que activó el modal
            var id = button.data('id') // Extraer la información de atributos de datos
            var modal = $(this)
            modal.find('#id_factura').val(id)
        })
        $("#eliminarDatos").submit(function(event) {
            var parametros = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "../ajax/anular_factura.php",
                data: parametros,
                beforeSend: function(objeto) {
                    $(".datos_ajax_delete").html('<img src="../../img/ajax-loader.gif"> Cargando...');
                },
                success: function(datos) {
                    $(".datos_ajax_delete").html(datos);
                    $('#dataDelete').modal('hide');
                    load(1);
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

function imprimir_factura(user_id) {
    VentanaCentrada('../pdf/documentos/corte_caja.php?user_id=' + user_id, 'Corte', '', '724', '568', 'true');
}