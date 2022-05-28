$(document).ready(() => {
    let date = new Date();

    let dia  = date.getDate();
    let mes  = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
    let anio = date.getFullYear();
    
    $('#emision').val(`${anio}-${mes}-${dia}`);
});