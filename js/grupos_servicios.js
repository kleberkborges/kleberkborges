$(document).ready(()=>{
    load();

    $('#nuevo_grupo_servicio').submit( e => {
        let data = $('#nuevo_grupo_servicio').serialize();
         $.ajax({
             url: '../ajax/nuevo_grupo_servicio.php',
             method: 'POST',
             data: data,
             beforeSend: () => {
                 $("#resultados_ajax_grupo_servicio").html('<img src="../../img/ajax-loader.gif"> Cargando...');
             },
             success: data => {
                 $("#resultados_ajax_grupo_servicio").html(data);
                load();
             }
         });
        e.preventDefault();
    });
});
function load() {
    $.ajax({
        url: '../ajax/buscar_grupos_servicios.php',
        method: 'GET',
        beforeSend: () => {
            $(".outer_div_grupos_servicios").html('<img src="../../img/ajax-loader.gif"> Cargando...');
        },
        success: data => {
            $('.outer_div_grupos_servicios').html(data);
        }
    });
}
