$(document).ready(()=>{
    $.ajax({
        method: 'GET',
        url: '../ajax/compras_semanales.php',
        success: data => {
            $('#compras_semanales').html(data);
        } 
    });

    $.ajax({
        method: 'GET',
        url: '../ajax/compras_mensuales.php',
        success: data => {
            $('#compras_mensuales').html(data);
        }
    });
    
    $.ajax({
        method: 'GET',
        url: '../ajax/compras_anuales.php',
        success: data => {
            $('#compras_anuales').html(data);
        }
    });
    $.ajax({
        method: 'GET',
        url: '../ajax/compras_diarias.php',
        success: (data) => {
            var content = document.getElementById('grafico_compras_diarias');
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