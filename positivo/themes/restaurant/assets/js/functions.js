/**
 * Created by Igniweb038 on 29/06/16.
 */
$(document).ready(function () {
    if ( $('[type="date"]').prop('type') != 'date' ) {
        // If not native HTML5 support, fallback to jQuery datePicker
        $('input[type=date]').datepicker({
            dateFormat: "yy-mm-dd ",
            firstDay: 1,
            dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            monthNames:
                ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                    "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthNamesShort:
                ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
                    "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
        });
    }
})

function notifications() {
    $.ajax({
        type: "get",
        url: "tables/ajax/notifications",
        success: function (data) {
            if (data > 0) {
                $('.notify-container').removeClass('hide');
                $('.notify-container .notify-alert').text(data);
                
                // vibrator api
                // var vibrate = navigator.vibrate || navigator.mozVibrate;
                // navigator.vibrate(800);
                
            } else {
                $('.notify-container').addClass('hide');
            }
        }
    });
}

$('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
});

// Add slideUp animation to dropdown
$('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
});