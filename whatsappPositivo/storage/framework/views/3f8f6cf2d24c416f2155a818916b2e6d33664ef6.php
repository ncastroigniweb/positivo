<div class="col-md-12 col-xs-12 col-sm-12" >
    <div id="btn-whatsapp-chat-sales" name="btn-whatsapp-chat-sales" onclick="dataLayer.push({'event':'clicChatWA'});">
        <div class="badge badge-danger" id= "btn-notification-message" style="background-color: red; border-radius: 50%; font-family:Arial, Helvetica, sans-serif;">
            <span>1</span>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    var datetime = new Date();
    var hour = datetime.getHours();
    if (hour < 10)
        hour = "0" + hour;

    var min = datetime.getMinutes();
    if (min < 10)
        min = "0" + min;

    $(function() {
        $('#btn-whatsapp-chat-sales').floatingWhatsApp({
            phone: '573016807667',
            popupMessage: 'Buenas tardes 👋, estoy feliz de hablar contigo. ¿Puedo ayudarle con algo? \n' + hour + ':' + min,
            message: "Escribe tu mensaje aquí",
            showPopup: true,
            showOnIE: false,
            headerTitle: '<img style= "border-image:white ;height: 50px; width: 50px; border-radius: 50%;" src="https://futy-io.s3.eu-west-2.amazonaws.com/media/3295/conversions/19pPAJOn8yJoWDvXlqRxM04r-avatar.jpg?v=1654889750"> German H. WP \n online',
            headerColor: 'rgb(37, 211, 102)',
            backgroundColor: 'rgb(32,191,85)',
            buttonImage: '<img src="http://whatsapp.positivo.co/social/img/icono.png" >'
        });
    });

    $('#btn-whatsapp-chat-sales').click(function(){
        console.log('chat de ventas');
        gtag("event", "click_whatsapp_chat_sales", {
            currency: "USD",
            value: 7.77,
            items: [
            {
                item_id: "SKU_12345",
                item_name: "Stan and Friends Tee",
            }
            ]
        });
    })

</script>

<?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/floatingchat.blade.php ENDPATH**/ ?>