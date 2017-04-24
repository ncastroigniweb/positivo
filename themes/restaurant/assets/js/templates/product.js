/**
 * Created by Igniweb038 on 02/07/16.
 */

$(document).ready(function () {

    $('#min').click(function () {
        //Solo si el valor del campo es diferente de 0
        if ($('#orp_cantidad').val() != 0)
        //Decrementamos su valor
            $('#orp_cantidad').val(parseInt($('#orp_cantidad').val()) - 1);
    });

    $('#plus').click(function () {
        //Aumentamos el valor del campo
        $('#orp_cantidad').val(parseInt($('#orp_cantidad').val()) + 1);
    });
});