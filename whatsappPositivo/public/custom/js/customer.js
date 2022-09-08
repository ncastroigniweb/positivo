//**********2021-12***************/
//************inputs dialog whatsapp-bot******************* */

//nuevo dialogo
$(document).on('click', '#crear_staticBackdrop_dialog', function(e) {

    e.preventDefault();

    var preguntas = $('#question .input-group:last');
    var nuevaPre = preguntas.clone();
    nuevaPre.find('input').val("");
    var indicePreguntas = parseInt(nuevaPre.attr('data-elemento')) + 1;
    nuevaPre.attr('data-elemento', indicePreguntas);
    console.log(indicePreguntas);
    $('#question').append(nuevaPre);

    var respuestas = $('#asnwer .input-group:last');
    var nuevaRes = respuestas.clone();
    nuevaRes.find('input').val("");
    var indiceRespuestas = parseInt(nuevaRes.attr('data-elemento')) + 1;
    nuevaRes.attr('data-elemento', indiceRespuestas);
    $('#asnwer').append(nuevaRes);

    var boton = $('#delete .eliminar:last');
    var botonclon = boton.clone();
    var indice = parseInt(botonclon.attr('data-elemento')) + 1;
    botonclon.attr('data-elemento', indice);
    botonclon.removeAttr('hidden');
    $('#delete').append(botonclon);

    var btnprueba = $('#delete .eliminar:first');
    btnprueba.removeAttr('hidden');

});

//eliminar dialogo
$(document).on('click', '.eliminar', function(e) {

    e.preventDefault();

    var dato = $(this).data('elemento');
    console.log(dato);

    $(this).remove();

    var cont = 0;

    var preguntaBorrar = $('#question .input-group');
    var resBorrar = $('#asnwer .input-group');

    preguntaBorrar.map(function(i, e) {
        if ($(this).attr('data-elemento') == dato) {
            $(this).remove();
        }
        cont++;
    });
    resBorrar.map(function(i, e) {
        if ($(this).attr('data-elemento') == dato) {
            $(this).remove();
        }
    });

    if (cont <= 2) {
        $('#delete .eliminar:first').attr('hidden', 'hidden');
    }

});


//lleva token hasta el ordercontroler a la funcion updatestatus
//añadir  clases particualres a los botnoes 
// $(document).on('click', '.estado', function(e) {
//     e.preventDefault();

//     let tkn = localStorage.getItem('fy_vk');
//     console.log(tkn);

//     $.ajax({
//         url: 'http://whatsapp.positivo.co/updatestatus/{alias}/{order}',
//         //url: "{{ route('order.status') }}",
//         method: "POST",
//         data: {
//             token: tkn,
//         },
//         success: function(data) {
//             if (data.error) {
//                 console.log('no hay token');
//             }
//             if (data.success) {
//                 console.log('bien');
//             }
//         }
//     });
// });