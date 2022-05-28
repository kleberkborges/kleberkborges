$(document).ready(() => {
    var div = $('#chart_div');
    $.ajax({
        url: '../ajax/obtener_compras_ventas_gastos.php',
        method: 'GET',
        success: res => {
            var res = JSON.parse(res);
            var data = [{
                type: 'pie',
                values: res.montos,
                labels: res.labels,
                textinfo: "label+percent",
                insidetextorientation: "radial"
            }];
            var layout = [{
                height: 700,
                width: 700
            }];
            
            Plotly.newPlot(div[0], data, layout);
        }
    });


});