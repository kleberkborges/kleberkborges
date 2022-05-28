$(document).ready(() => {
    $.ajax({
        url: '../ajax/gastos_dia.php',
        method: 'GET',
        success: data => {
            $('#gastos_dia').html(data);
        }
    });
    $.ajax({
        url: '../ajax/gastos_semanales.php',
        method: 'GET',
        success: data => {
            $('#gastos_semanales').html(data);
        }
    });
    $.ajax({
        url: '../ajax/gastos_mensuales.php',
        method: 'GET',
        success: data => {
            $('#gastos_mensuales').html(data);
        }
    });
    $.ajax({
        url: '../ajax/gastos_anuales.php',
        method: 'GET',
        success: data => {
            $('#gastos_anuales').html(data);
        }
    });
    $.ajax({
        method: 'GET',
        url: '../ajax/gastos_diarios.php',
        success: (data) => {
            var content = document.getElementById('grafico_gastos_diarios');
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