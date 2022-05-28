$(document).ready(function () {
    $("#cod_resultado").load("../ajax/incrementa_cod_prod.php");
    load_productos(1);
});

function load_productos(page) {
    var q = $("#q_producto").val();
    var categoria = $("#categoria").val();
    $("#loader_producto").fadeIn('slow');
    $.ajax({
        url: `../ajax/carga_ajuste.php?action=ajax&page=${page}&q=${q}&categoria=${categoria}`,
        beforeSend: function (objeto) {
            $('#loader_producto').html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_productos").html(data).fadeIn('slow');
            $('#loader_producto').html('');
        }
    })
}

function obtener_id(id) {
    return id_producto = id;
}

$("#add_stock").submit(function (event) {
    event.preventDefault();
    $('#guardar_datos').attr("disabled", true);
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: `../ajax/agregar_stock.php?id=${id_producto}`,
        data: parametros,
        beforeSend: function (objeto) {
            $("#resultados_ajax").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function (datos) {
            $("#resultados_ajax").html(datos);
            $('#guardar_datos').attr("disabled", false);
            $('#add-stock').modal('hide');
            // $("#outer_div").load("../ajax/ver_historial.php");
            load_productos(1);
            //resetea el formulario
            $("#add_stock")[0].reset();
            //desaparecer la alerta
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 5000);
        }
    });
});

$("#remove_stock").submit(function (event) {
    event.preventDefault();
    $('#eliminar_datos').attr("disabled", true);
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: `../ajax/eliminar_stock.php?id=${id_producto}`,
        data: parametros,
        beforeSend: function () {
            $("#resultados_ajax").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function (datos) {
            $("#resultados_ajax").html(datos);
            $('#eliminar_datos').attr("disabled", false);
            $('#remove-stock').modal('hide');
            //$("#outer_div").load("../ajax/ver_historial.php");
            load_productos(1);
            //resetea el formulario
            $("#remove_stock")[0].reset();
            //desaparecer la alerta
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 5000);
        }
    });
});

function get_historial (id_producto) {
    var tipo = $("#tipo").val();
    parametros = {
        "action": "ajax",
        'tipo': tipo
    }
    $.ajax({
        url: `../ajax/cargar_historial.php?id=${id_producto}`,
        data: parametros,
        beforeSend: () => {
            $("#outer_div").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: (datos) => {
            $("#outer_div").html(datos);
        }
    });
}