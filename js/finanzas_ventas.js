$(document).ready(() => {
    $.ajax({
        method: 'GET',
        url: '../ajax/ventas_semanales.php',
        success: (data) => {
            $('#ventas_semanales').html(data);
        }
    });
    $.ajax({
        method: 'GET',
        url: '../ajax/ventas_mensuales.php',
        success: (data) => {
            $('#ventas_mensuales').html(data);
        }
    });
    $.ajax({
        method: 'GET',
        url: '../ajax/ventas_anuales.php',
        success: (data) => {
            $('#ventas_anuales').html(data);
        }
    });
    $.ajax({
        method: 'GET',
        url: '../ajax/ventas_diarias.php',
        success: (data) => {
            var content = document.getElementById('grafico_ventas_diarias');
            data = JSON.parse(data);
            Plotly.newPlot(content, [{
                y: data.totales,
                x: data.fechas,
                type: 'scatter',
                mode: 'lines'
            }], {
                margin: { t: 0 }
            },
            {
                showSendToCloud: true
            });
        }
    });
});