/**
 * Created by Igniweb038 on 24/06/16.
 */

$(document).ready(function () {
    $("#myButton").click(function () {
        $("#myButton").addClass("loading");
    });

    $('#min').click(function(){
        //Solo si el valor del campo es diferente de 0
        if ($('#orp_cantidad').val() != 0)
        //Decrementamos su valor
            $('#orp_cantidad').val(parseInt($('#orp_cantidad').val()) - 1);
    });

    $('#plus').click(function(){
        //Aumentamos el valor del campo
        $('#orp_cantidad').val(parseInt($('#orp_cantidad').val()) + 1);
    });

    $('#enviar').click(function(){

        var quantity = $('#orp_cantidad').val();
        var dataString = 'orp_cantidad='+quantity;

        $.ajax({
            type: "POST",
            url: "quantity.php",
            data: dataString,
            success: function(data) {
                $('#result').fadeIn(1000).html(data);
            }
        });
    });

    $('.button-change-waiter').click(function () {
        if($('.section-change_waiter').hasClass('hide')){
            $('.section-change_waiter').fadeIn('slow').removeClass('hide');
        } else {
            $('.section-change_waiter').addClass('hide');
        }
    });

    $('.button-change-table').click(function () {
        if($('.section-change_table').hasClass('hide')){
            $('.section-change_table').fadeIn('slow').removeClass('hide');
        } else {
            $('.section-change_table').addClass('hide');
        }
    });
    
    $('.button-search').click(function () {
        if($('.adder').hasClass('hide')){
            $('.adder').fadeIn('slow').removeClass('hide');
            $('#bs-prods').focus();
        } else {
            $('.adder').addClass('hide');
            $('#bs-prods').val('');
            $('.registros').addClass('hide');
            $('.registros .quick-add').html('');
            $('#form-add').addClass('hide');
        }
    });
    
    $('.title-tabs .ng-scope').on( "click", function() {
        $('.products').html('');
    });
    
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
          clearTimeout (timer);
          timer = setTimeout(callback, ms);
        };
    })();
    
    $('#bs-prods').keyup(function() {
        delay(function(){
          searchProduct($('#bs-prods'));
        }, 500 );
    });
});

function activarBoton() {
    if (verificar()) {
        myButton.disabled = false
    }
    else {
        myButton.disabled = true
    }
}

function verificar() {
    if (mes_personas.value === "")
        return false;

    return true;
}

function imprimir() {
    if (parseInt(navigator.appVersion) > 4)
        window.print();
}

function asegurar() {
    rc = confirm("Seguro que desea eliminar?");
    return rc;
}

function cambiarprecio() {
    var seleccion = document.getElementById('select1');

    document.getElementById('text3').value = seleccion.options[seleccion.selectedIndex].id;
    if (seleccion.value == "1") {
        divC = document.getElementById("nCuenta");
        divC.style.display = "";

        divT = document.getElementById("nTargeta");
        divT.style.display = "none";
        divIGV = document.getElementById("nIgv");
        divIGV.style.display = "";


    } else {

        divC = document.getElementById("nCuenta");
        divC.style.display = "none";

        divT = document.getElementById("nTargeta");
        divT.style.display = "";
        divIGV = document.getElementById("nIgv");
        divIGV.style.display = "none";

    }
}

function suma() {
    var sum1 = document.getElementById("sum1");
    var sum2 = document.getElementById("sum2");

    var num1 = sum1.value;
    var num2 = sum2.value;

    var float1 = num1.toString().replace('.','');
    var float2 = num2.toString().replace('.','');

    var div = document.getElementById("resultado");
    var resultado = parseFloat(float2) - parseFloat(float1);
    div.innerHTML = resultado;
}

function searchProduct(element){
    var text = $(element).val();
    text = text.replace('#', '-n-');
    text = text.replace('(', '-pa-');
    text = text.replace(')', '-pc-');
    text = text.replace('&', '-y-');
    
    if(text != '') {
        $.ajax({
            type: "get",
            url: 'tables/ajax/search_product/'+ text,
            dataType: "json",
            beforeSend: function(){
                $('.registros').fadeIn('slow').removeClass('hide');
            },
            success: function (data) {
                // Clean elements
                $('.registros .quick-add').html('');
                
                if(data){
                    $.each(data, function(index, value) {
                        var html = '\
                            <button onclick="viewDetail(this)" style="width: 49%;" price="'+value.final_price+'" class="button ng-scope" title="'+value.name+'" type="button">\
                                <span class="product-code ng-binding ng-scope">'+value.id+'</span>\
                                <span class="product-label ng-binding ">'+value.name+'</span>\
                            </button>';

                        $('.registros .quick-add').append(html);
                    });
                }
            }
        });
    }
}

function viewDetail(element_js){
    var element = $(element_js);

    //get info product
    var id = element.find('.product-code').text();
    var table_id = $('#table_id').val();
    var name = element.attr('title');
    var price = element.attr('price');

    // Show form
    $('#add2order').fadeIn('slow').removeClass('hide');

    //set public info to add form
    $('.form-product-name').text(name);
    $('.form-product-price').text(price);

    $('#add2order').attr('action','tables/product/add2order/'+id+'/'+table_id);
}

function cancelDetail(element){
    $(element).parents('#add2order').addClass('hide');
}

function ajax_show_products(id_category){
    $.ajax({
        type: "get",
        url: 'tables/ajax/show_products/'+ id_category,
        dataType: "json",
        beforeSend: function(){
            // Clean elements

            if($('.products').hasClass('hide')){
                $('.products').fadeIn('slow').removeClass('hide');
            } else {
                $('.products').addClass('hide');
            }
        },
        success: function (data) {
            
            $('.products').html('');
            var html = '';
            
            $.each(data, function(index, product) {
                
                var name_class = '';
                if(product.status == false){
                    name_class = 'disable-div-product';
                }else{
                    name_class = 'able-product';
                }
                
                var onclick = '';
                if (product.status == true) {
                    onclick = 'onclick="charge_product('+ product.id +',\''+ product.name +'\',\''+ product.symbol + product.price +'\')"';
                }
                
                html += '<li>' +
                        '<div id="product_'+product.id+'" class="proBox ' + name_class + '" '+ onclick +'>';
                
                    html += '<div class="all_proImg">' +
                                '<img class="img-rounded u-image-100-table"' +
                                    'src="assets/uploads/' + product.image + '">' +
                                '<p class="addFav"></p>' +
                            '</div>' +
                            '<div class="all_proNam">' +
                                product.name +
                            '</div>' +
                            '<div class="all_price">';

                            if( product.price != 0){
                                html += '<span class="bz_icon">' + (product.symbol) + '</span>' +
                                '<span class="my_shop_price">' + (product.price) + '</span>' ;
                            }
                            html += '</div>';
                            if (product.status == false) { 
                                html += '<div class="all_status">' +
                                            '<span class="product-status">' + (product.lang_product_unavailable) + '</span>' +
                                        '</div>';
                            }else{
                                var table_id = $('#table_id').val();
                                
                                if(product.options){
                                    html+= '<button class="more_options" href="javascript:void(0)" onclick="details_product('+product.id+','+table_id+')"><div>'+
                                            '<i class="fa fa-list-ul" aria-hidden="true"></i>' +
                                       '</button></a>';
                                }
                                
                                html += '<button id="add_product_'+product.id+'" class="button button-action add_product" onclick="ajax_add_product('+ product.id +','+ table_id +')">' +
                                            '<span class="icon icon-plus u-icon-user-plus"></span>' +
                                        '</button>';        
                            }
                                
                    html += '</div>' +
                '</li>';
            });
            $('.products').append(html);
            
            if($('.products').hasClass('hide')){
                $('.products').fadeIn('slow').removeClass('hide');
            } else {
                $('.products').addClass('hide');
            }
        }
    });
}

function charge_product(id, name, price){

    if($('.adder').hasClass('hide')){
        $('.adder').fadeIn('slow').removeClass('hide');
    }
    
    if(!$('.registros').hasClass('hide')){
        $('.registros').fadeIn('slow').addClass('hide');
    }
    
    if($('#agrega-registros2').hasClass('hide')){
        $('#agrega-registros2').fadeIn('slow').removeClass('hide');
    }
    
    $('#bs-prods').removeAttr( "autofocus" );
    
    $('.registros .quick-add').html('');
    
    var html = '\
        <button id="charge_product_'+ id +'" onclick="viewDetail(this)" style="width: 49%;" price="'+price+'" class="button ng-scope" title="'+name+'" type="button">\
            <span class="product-code ng-binding ng-scope">'+id+'</span>\
            <span class="product-label ng-binding ">'+name+'</span>\
        </button>';

    $('.registros .quick-add').append(html);
    
    $('#charge_product_'+id).click();
    
    $('html, body').animate({
        scrollTop: $("#agrega-registros2").offset().top
    }, 1000);
}

function ajax_add_product(id, table_id){
    var e=window.event||arguments.callee.caller.arguments[0];
    e.cancelBubble = true;
    e.returnValue = false;
    if (e.stopPropagation) e.stopPropagation();
    if (e.preventDefault) e.preventDefault();
    
    $.ajax({
        type: "get",
        url: 'tables/ajax/add_product/'+id+'/'+table_id,
        dataType: "json",
        beforeSend: function(){
            $("#add_product_"+id).addClass("loading");
            
            $('#add_product_'+id).prop('disabled',true);
        },
        success: function (data) {
            
            if(data != null){
                html = '<li class="ng-scope '+data.product_status+'">';
                    html += '<div class="item">' +
                                '<span class="count ng-binding ">'+data.quantity+'</span>' +
                                '<span class="item-label">' +
                                    '<span title="" class="ng-binding ">' +
                                        '<strong>';
                                        if(!data.in_array){
                                            html += '<i class="fa fa-bookmark icon-explain-product" aria-hidden="true"></i>&nbsp;';
                                        }
                                        html += data.product_name + '&nbsp;' + 
                                        '</strong>' +
                                    '</span>' +
                                '</span>' +
                                '<span class="price ng-binding ">'+data.symbol+data.unit_price+'</span>' +
                                '<span class="price ng-binding ">'+data.symbol+data.subtotal+'</span>' +
                                '<button class="button-icon button-simple ng-scope  ng-isolate-scope" onclick="window.location.href=\'tables/product/remove/'+data.id+'/'+data.table+'\'">' +
                                    '<span class="icon icon-x"></span>' +
                                '</button>' +
                            '</div>' +
                            '<div class="addition-comment ng-hide "><p class="ng-binding "></p></div>' +
                        '</li>';

                $('#products_order').append(html);
                
                var total = parseFloat( $('#products_added').attr('total') ) + parseFloat(data.subtotal);
                
                $('#products_added').attr('total', total);
                $('#products_added').text(data.symbol+total);
                
                var button = '<button id="product_confirm" onclick="window.location.href=\'tables/order/confirm/'+data.table+'\'" class="button button-action btn-confirm-3 u_float_left">' +
                        '<i class="fa fa-exclamation-triangle faa-flash animated icon-alert-confirm" aria-hidden="true"></i> '+ data.text_confirm +
                        '</button>';
                
                if(!$('#product_confirm').length){
                    $('.button-container.end div[ert-can="create_discounts"]').after(button);
                }
                
                var notify = '<div id="notify_add_'+id+'" class="notify alert alert-success">' +
                        '<strong><i class="fa fa-check" aria-hidden="true"></i></strong>' +
                        '</div>';
                
                $('#product_'+id).append(notify);
                
                setTimeout(function(){
                    $("#add_product_"+id).removeClass("loading");
                    $('#add_product_'+id).prop('disabled',false);
                    $('#notify_add_'+id).remove();
                }, 2000);
            }
        }
    });
}

function details_product(id, table){
    var e=window.event||arguments.callee.caller.arguments[0];
    e.cancelBubble = true;
    e.returnValue = false;
    if (e.stopPropagation) e.stopPropagation();
    if (e.preventDefault) e.preventDefault();
    
    window.location = 'tables/product/view/'+id+'/'+table;
}