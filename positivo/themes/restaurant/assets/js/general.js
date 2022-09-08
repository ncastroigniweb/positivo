/**
 * Created by Igniweb038 on 05/08/16.
 */
$(document).ready(function() {
    $('#div-btn1').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-usuario.php",
            success: function(a) {
                $('#div-results').html(a);
            }
        });
    });

    

    $('#div-btnroles').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-roles.php",
            success: function(a) {
                $('#div-resultsroles').html(a);
            }
        });
    });

    $('#div-btncategorias').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-categorias.php",
            success: function(a) {
                $('#div-resultscategorias').html(a);
            }
        });
    });

    $('#div-btncategoriasbebidas').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-categoriasbebidas.php",
            success: function(a) {
                $('#div-resultscategoriasbebidas').html(a);
            }
        });
    });

    $('#div-btnmesas').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-mesas.php",
            success: function(a) {
                $('#div-resultsmesas').html(a);
            }
        });
    });

    $('#div-btnplatos').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-platos.php",
            success: function(a) {
                $('#div-resultsplatos').html(a);
            }
        });
    });

    $('#div-btnbebidas').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-bebidas.php",
            success: function(a) {
                $('#div-resultsbebidas').html(a);
            }
        });
    });

    $('#div-btnclientes').click(function(){
        $.ajax({
            type: "POST",
            url: "registrar-clientes.php",
            success: function(a) {
                $('#div-resultsclientes').html(a);
            }
        });
    });

    $('[data-toggle="tooltip"]').tooltip();

    // init notifications
    // notificaciones();

    // recurrent notifications
    // setInterval( notificaciones, 3000);
});