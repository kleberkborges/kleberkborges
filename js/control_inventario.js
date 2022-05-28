$(document).ready(function () {
    $("#cod_resultado").load("../ajax/incrementa_cod_prod.php");
    load_productos(1);
});

function load_productos(page) {
    var q = $("#q_producto").val();
    var categoria = $("#categoria").val();
    $("#loader_producto").fadeIn('slow');
    $.ajax({
        url: `../ajax/carga_productos_control_inventario.php?action=ajax&page=${page}&q=${q}&categoria=${categoria}`,
        beforeSend: function (objeto) {
            $('#loader_producto').html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_productos").html(data).fadeIn('slow');
            $('#loader_producto').html('');
        }
    })
}
function get_control_inventario(id_producto) {
    var tipo = $("#tipo").val();
    parametros = {
        "action": "ajax",
        'tipo': tipo
    }
    $.ajax({
        url: `../ajax/cargar_control_inventario.php?id=${id_producto}`,
        data: parametros,
        beforeSend: () => {
            $("#outer_div").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: (datos) => {
            $("#outer_div").html(datos);
        }
    });
}