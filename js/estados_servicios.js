$(document).ready(()=>{
    load();
    $('#nuevo_estado_servicio').submit(e => {
        let data = $('#nuevo_estado_servicio').serialize();
        $.ajax({
            url: '../ajax/nuevo_estado_servicio.php',
            method: 'POST',
            data: data,
            beforeSend: () => {
                $('#resultados_ajax_estado_servicio').html('<img src="../../img/ajax-loader.gif"> Cargando...');
            },
            success: data => {
                console.log(data);
                $('#resultados_ajax_estado_servicio').html(data);
                load();
            }
        });
        e.preventDefault();
    });
});
function load() {
    $.ajax({
        url: '../ajax/buscar_estados_servicios.php',
        method: 'GET',
        beforeSend: () => {
            $('.outer_div_estados_servicios').html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: data => {
            $('.outer_div_estados_servicios').html(data);
        }
    });
}