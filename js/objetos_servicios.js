$(document).ready(()=>{
    load();
    $('#nuevo_objeto_servicio').submit(e => {
        e.preventDefault();
        let data = $('#nuevo_objeto_servicio').serialize();
        $.ajax({
            url: '../ajax/nuevo_objeto_servicio.php',
            method: 'POST',
            data: data,
            beforeSend: () => {
                $('#resultados_ajax_objeto_servicio').html('<img src="../../img/ajax-loader.gif"> Cargando...');
            },
            success: data => {
                $('#resultados_ajax_objeto_servicio').html(data);
                load();
            }
        });
    });
});

function load() {
    $.ajax({
        url: '../ajax/buscar_objetos_servicios.php',
        method: 'GET',
        beforeSend: () => {
            $('.outer_div_objetos_servicios').html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: data => {
            $('.outer_div_objetos_servicios').html(data);
        }
    });
}