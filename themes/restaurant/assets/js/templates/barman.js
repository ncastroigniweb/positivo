/**
 * Created by Igniweb038 on 24/06/16.
 */

// Each 10 seg , update kitchen list
$(document).ready(function () {
    setInterval(ajaxCall, 5000);
    setInterval(update_average, 5000);

    $('.slide-down').click(function(){
        $('.tables-content').slideToggle("slow");
        if($(this).find('i').hasClass('fa-chevron-down')){
            $(this).find('i').removeClass("fa-chevron-down").addClass('fa-chevron-up');
        } else {
            $(this).find('i').removeClass("fa-chevron-up").addClass('fa-chevron-down');
        }

    });
    
    $('.slide-left').click(function(){
        $( 'main' ).css( "transition", "initial" );
        if($(this).find('i').hasClass('fa-chevron-left')){
            $(this).find('i').removeClass("fa-chevron-left").addClass('fa-chevron-right');
            $('.container-main').animate({'width':'65%'},"slow");
            $('.container-bar-right').animate({'width':'35%'},"slow"); 
        } else {
            $(this).find('i').removeClass("fa-chevron-right").addClass('fa-chevron-left');
            $('.container-bar-right').animate({'width':'0'},"slow");
            $('.container-main').animate({'width':'100%'},"slow");
        }
        $( 'main' ).css( "transition", "width 0.2s" );
    });
});

Array.prototype.inArray = function (value)
{
    // Returns true if the passed value is found in the
    // array. Returns false if it is not.
    var i;
    for (i=0; i < this.length; i++)
    {
        if (this[i] == value)
        {
            return true;
        }
    }
    return false;
};

function product_dispatch(id_product) {
    $.ajax({
        type: "get",
        url: 'barman/ajax/dispatch/'+id_product,
        dataType: "json",
        success: function (data) {
            if (data){
                $("#"+id_product).attr('no-remove','no-remove');
                $("#button_"+id_product).addClass('btn-info');
                $("#button_"+id_product+" span").addClass('icon-clock');
                $("#button_"+id_product+" span").addClass('button-dispatched');
                $("#button_"+id_product+" span").attr('id_product',id_product);
                $("#button_"+id_product).removeAttr('onclick');

                $('.button-dispatched').click(function(){
                    var product = $(this).attr('id_product');
                    $('#'+product).remove();
                });
            }
        }
    });
}

function ajaxCall() {

    var language_js = $('#language-js').val();

    $.ajax({
        type: "get",
        url: 'barman/ajax/list_products',
        dataType: "json",
        success: function (data) {

            // Get exist productd
            var exist_product = [];

            $.each(data, function(index, value) {
                exist_product.push(value.id);
                if($('#'+value.id).length > 0){
                    $('#minutes_'+value.id).text(value.diff_minutes);
                    $('#table_'+value.id).text(value.table_name);
                } else {
                    
                    var highligted ='';
                    
                    if (parseInt(value.subcategory_id) === 27){
                        highligted = 'bisque';
                    }
                    
                    if (parseInt(value.subcategory_id) === 28){
                        highligted = 'darksalmon';
                    }
                    
                    var html = '<tr id="'+value.id+'" class="status-in_course '+highligted+'">\
                        <td class="ng-binding ">\
                            <button id="btn-waiting" class="btn btn-info ng-scope  ng-isolate-scope" >\
                                <div id="table_'+ value.id +'">'+value.table_name+'</div>\
                            </button>\
                        </td>\
                        <td class="ng-binding ">\
                            '+value.waiter_name+'\
                        </td>\
                        <td>\
                            <div class="bx-viewport bx-viewport-container">\
                                <img src="assets/uploads/'+ value.image+'\" height="65" width="72">\
                            </div>\
                        </td>\
                        <td class="col-id ng-binding category-chef-barman u_font_20">\
                            '+ parseInt(value.quantity)+'\
                        </td>\
                        <td>\
                            <div class="ng-binding  ng-isolate-scope u_font_20">\
                                <strong class="item-label">\
                                    <span title="'+value.product_name+'" class="ng-binding ">\
                                        '+value.product_name+'\
                                    </span>\
                                </strong>';
                    
                                if (value.option_name !== null){
                                    var html = html + '<i>('+value.option_name+')</i>';
                                }
                                
                            var html = html + '</div>\
                            <div class="addition-comment ng-hide">\
                                <p class="ng-binding">'+value.comments+'</p>\
                            </div>\
                        </td>\
                        <td id="minutes_'+value.id+'">'+value.diff_minutes+'</td>\
                        <td>\
                            <button id="button_'+value.id+'" class="btn btn-success ng-scope  ng-isolate-scope"\
                                onclick="click_once(this); product_dispatch('+value.id+')">\
                                <span class="icon icon-check button-dispatch"></span>\
                            </button>\
                        </td>\
                    </tr>';

                    $('.body-list').append(html);
                }
            });

            // if doesnt exist product in the new list remove it
            $('.body-list tr').each(function() {
                if(!exist_product.inArray($( this ).attr('id'))){
                    if ( $('#'+ $( this ).attr('id') ).attr('no-remove') != "no-remove" ){
                        $( this ).remove();
                    }
                }
            });
        }
    });
}

function switch_right_view(id_user, view_right) {
    $.ajax({
        type: "get",
        url: 'barman/ajax/update_view_right/'+id_user+'/'+view_right,
        
        success: function (response) {
            
            if(response !== 'fallo'){
                $('#slide-left').attr("onclick", "switch_right_view("+id_user+", "+response+")");
            }else{
                console.log("no actualizado");
            }
            
        }
    });
}

function update_average() {
    $.ajax({
        type: "get",
        url: 'barman/ajax/update_average/',
        
        success: function (response) {
            
            if(response){
                $('.average').text(response);
            }
            
        }
    });
}

function hide_average(){
    $('.average_time').hide();
    
    setTimeout("$('.average_time').show()",4000);
}

function click_once(object) {
    object.onclick=null;
}