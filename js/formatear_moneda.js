function formatear_moneda(input) {
    $(`#${input}`).on('keyup', () => {
        if ($(`#${input}`).val() != null && $(`#${input}`).val() != 0){
            var precio = $(`#${input}`).val().replace('.', '');
            var data = {
                "precio": precio
            }
            $.ajax({
                method: 'POST',
                url: '../ajax/formatear_moneda.php',
                data: data,
                success: data => {
                    console.log(typeof data);
                    $(`#${input}`).val(data);
                }

            });
        } else {
            $(`#${input}`).val(0);
        }
    });
}