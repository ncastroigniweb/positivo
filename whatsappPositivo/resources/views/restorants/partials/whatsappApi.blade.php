<div class="card card-profile bg-secondary shadow">
    <div class="card-header">
        <figure>
            <blockquote class="blockquote">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8.5 1.866a1 1 0 1 0-1 0V3h-2A4.5 4.5 0 0 0 1 7.5V8a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1v-.5A4.5 4.5 0 0 0 10.5 3h-2V1.866ZM14 7.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.5A3.5 3.5 0 0 1 5.5 4h5A3.5 3.5 0 0 1 14 7.5Zm-8 5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5ZM3 8.062C3 6.76 4.235 5.765 5.53 5.886a26.58 26.58 0 0 0 4.94 0C11.765 5.765 13 6.76 13 8.062v1.157a.933.933 0 0 1-.765.935c-.845.147-2.34.346-4.235.346-1.895 0-3.39-.2-4.235-.346A.933.933 0 0 1 3 9.219V8.062Zm4.542-.827a.25.25 0 0 1 .182.135l.842 1.7.754-.785a.25.25 0 0 1 .166-.076 24.85 24.85 0 0 0 1.98-.19.25.25 0 0 1 .068.496 25.29 25.29 0 0 1-1.922.187l-.932.971a.25.25 0 0 1-.404-.062l-.847-1.71-.754.736a.25.25 0 0 1-.189.07 25.36 25.36 0 0 1-2.02-.192.25.25 0 1 1 .068-.495c.512.07 1.143.138 1.87.182l.921-.9a.25.25 0 0 1 .217-.067Z" />
                    </svg> {{ __("Settings whatsapp")}}
                </p>
            </blockquote>
            <figcaption class="blockquote-footer">
                Bot Api. <cite title="Source Title">Gestión automática de whatsapp</cite>
            </figcaption>
        </figure>
    </div>
    <div class="alert alert-warning" id="alertWhats" style="display: none" role="alert">
            <p>Mensaje.</p>        
            <ul></ul> 
        </div>
    @if (!$restorant->keyWhatsApi)       
    <button id="waApi" class="btn btn-success">Registrar Comercio</button>
    <button type="submit" id="regKey" style="display:none" class="btn btn-lg btn-success">Continuar</button>
    @else
        <div class="container" style="margin: 20px;">
            <div class="row text-center">
                <div class="col-md-3">
                    <a class="btn btn-lg" id="genToken" style="background: #7381e5; color: #fff;" href="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
                        <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                        <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        Token
                    </a>
                </div>
                <div class="col-md-3" id="iat">
                    <p class="font-monospace pt-2"></p>
                </div>
                <div class="col-md-3" id="exp">
                    <p class="font-monospace pt-2"></p>
                </div>
                <div class="col-md-1" id="timeV">

                </div>
            </div>
        </div>

        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column" id="cont-opt">
        <main class="px-3">
            <div class="container text-black bg-white rounded" style="padding: 30px;">
                <div class="row">
                    <div class="col-md-6 text-center">                        
                        <p class="lead">
                            <a  class="btn btn-info text-white" id="dialog" data-toggle="modal" data-target="#staticBackdrop_dialog">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </a>
                        <figcaption class="blockquote-footer">
                            Clic <cite title="Source Title"> para actualizar el dialogo del bot</cite>
                        </figcaption>
                        </p>
                        
                    </div>
                    <div class="col-md-6 text-center pt-3" id="det_ses" style="display: none">
                        <div class="detail">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <small><i class="fa fa-plug" aria-hidden="true"></i> State:</small> 
                                    <small id="state" class="state fw-6 text-success"> </small>
                                    <small><i class="fa fa-battery-full" aria-hidden="true"></i> Battery: </small>
                                    <small id="battery" class="battery fw-6 text-success"> </small>
                                </li>                                
                                <li class="list-group-item">
                                    <small id="dateSes"></small>
                                </li>                             
                           </ul>
                        </div>
                    </div>
                    <div class="col-md-6 text-center" style="display: none" id="scan_qr">                                             
                        <p class="lead" >
                            <a class="btn btn-info text-white" id="qr" data-toggle="modal" data-target="#staticBackdrop_qr">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM3 3h3v3H3V3Zm0 7h3v3H3v-3Zm7-7h3v3h-3V3ZM7 2H2v5h5V2Zm0 7H2v5h5V9Zm2-7h5v5H9V2ZM4 4h1v1H4V4Zm7 0h1v1h-1V4Zm-6 7H4v1h1v-1Zm3.5-3H8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8.5Zm1.5 2V9H9v1h1Zm4 3.5V12h-1v1h-2v1h3v-.5Zm-4 .5v-1H8v1h2Zm2-5h2V8h-2v1Z" />
                        </svg>
                            </a>                     
                        <figcaption class="blockquote-footer">
                            Clic <cite title="Source Title"> para vincular el dispositivo {{ $restorant->whatsapp_phone }}</cite>
                        </figcaption>
                        </p>
                    </div>
                    <div class="col-md-6 text-center" id="spinerVal"> 
                        <p class="lead">
                            <i class="fa fa-spinner fa-spin" style="color: #11cdef; font-size: 40px;"></i>                        
                        </p>                       
                        <figcaption class="blockquote-footer">
                            Validando <cite title="Source Title">sesión {{ $restorant->whatsapp_phone }}</cite>
                        </figcaption>
                    </div>                   
                </div>
            </div>
        </main>
    </div>
    @endif     


<!-- Modal Dialog -->
<div class="modal fade" id="staticBackdrop_dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><i class="ni ni-bullet-list-67"></i> Dialogo del bot</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="conver">
            <div id="question" class="col-md-3">                
                <h6 class="heading-small text-muted">Pregunta</h6>   
                          
            </div>
            <div id="asnwer" class="col-md-7">
                <h6 class="heading-small text-muted"> Respuesta </h6>           
            </div>
            <div id="delete" class="col-md-2">
                <h6 class="heading-small text-muted"> Borrar </h6>           
            </div>
        </div>
      </div>
      <div style="text-align: center">
        <div class="modal-footer" style="display: inline;  float: left">
            <button type="button" class="btn btn-success" id="crear_staticBackdrop_dialog" style="top: -40px"><b>+</b></button>
        </div>
        <div class="modal-footer" style="display: inline; float: right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="actualizar_staticBackdrop_dialog">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
</div>
    <!-- End Modal Dialog  -->
<!-- Modal Qr -->
<div class="modal fade" id="staticBackdrop_qr" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">                   
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
      <div class="modal-body">
        <div class="cover-container d-flex w-100 h-100  mx-auto flex-column">
            <main class="px-3">
                <div class="container text-black bg-white rounded">
                    <div class="row">
                        <div class="col-md-6">                            
                            <ol class="_2A31C" style="padding: 80px 0 0 0;">
                                <li class="QtrYx">Abre WhatsApp en tu teléfono</li>
                                <li class="QtrYx"><span dir="ltr" class="i0jNr">Toca <strong><span dir="ltr"
                                                class="i0jNr">Menú <span class="_30yMe"><svg height="24px"
                                                        viewBox="0 0 24 24" width="24px">
                                                        <rect fill="#f2f2f2" height="24" rx="3" width="24"></rect>
                                                        <path
                                                            d="m12 15.5c.825 0 1.5.675 1.5 1.5s-.675 1.5-1.5 1.5-1.5-.675-1.5-1.5.675-1.5 1.5-1.5zm0-2c-.825 0-1.5-.675-1.5-1.5s.675-1.5 1.5-1.5 1.5.675 1.5 1.5-.675 1.5-1.5 1.5zm0-5c-.825 0-1.5-.675-1.5-1.5s.675-1.5 1.5-1.5 1.5.675 1.5 1.5-.675 1.5-1.5 1.5z"
                                                            fill="#818b90"></path>
                                                    </svg></span></span></strong> o <strong><span dir="ltr"
                                                class="i0jNr">Configuración <span class="_30yMe"><svg width="24" height="24"
                                                        viewBox="0 0 24 24">
                                                        <rect fill="#F2F2F2" width="24" height="24" rx="3"></rect>
                                                        <path
                                                            d="M12 18.69c-1.08 0-2.1-.25-2.99-.71L11.43 14c.24.06.4.08.56.08.92 0 1.67-.59 1.99-1.59h4.62c-.26 3.49-3.05 6.2-6.6 6.2zm-1.04-6.67c0-.57.48-1.02 1.03-1.02.57 0 1.05.45 1.05 1.02 0 .57-.47 1.03-1.05 1.03-.54.01-1.03-.46-1.03-1.03zM5.4 12c0-2.29 1.08-4.28 2.78-5.49l2.39 4.08c-.42.42-.64.91-.64 1.44 0 .52.21 1 .65 1.44l-2.44 4C6.47 16.26 5.4 14.27 5.4 12zm8.57-.49c-.33-.97-1.08-1.54-1.99-1.54-.16 0-.32.02-.57.08L9.04 5.99c.89-.44 1.89-.69 2.96-.69 3.56 0 6.36 2.72 6.59 6.21h-4.62zM12 19.8c.22 0 .42-.02.65-.04l.44.84c.08.18.25.27.47.24.21-.03.33-.17.36-.38l.14-.93c.41-.11.82-.27 1.21-.44l.69.61c.15.15.33.17.54.07.17-.1.24-.27.2-.48l-.2-.92c.35-.24.69-.52.99-.82l.86.36c.2.08.37.05.53-.14.14-.15.15-.34.03-.52l-.5-.8c.25-.35.45-.73.63-1.12l.95.05c.21.01.37-.09.44-.29.07-.2.01-.38-.16-.51l-.73-.58c.1-.4.19-.83.22-1.27l.89-.28c.2-.07.31-.22.31-.43s-.11-.35-.31-.42l-.89-.28c-.03-.44-.12-.86-.22-1.27l.73-.59c.16-.12.22-.29.16-.5-.07-.2-.23-.31-.44-.29l-.95.04c-.18-.4-.39-.77-.63-1.12l.5-.8c.12-.17.1-.36-.03-.51-.16-.18-.33-.22-.53-.14l-.86.35c-.31-.3-.65-.58-.99-.82l.2-.91c.03-.22-.03-.4-.2-.49-.18-.1-.34-.09-.48.01l-.74.66c-.39-.18-.8-.32-1.21-.43l-.14-.93a.426.426 0 00-.36-.39c-.22-.03-.39.05-.47.22l-.44.84-.43-.02h-.22c-.22 0-.42.01-.65.03l-.44-.84c-.08-.17-.25-.25-.48-.22-.2.03-.33.17-.36.39l-.13.88c-.42.12-.83.26-1.22.44l-.69-.61c-.15-.15-.33-.17-.53-.06-.18.09-.24.26-.2.49l.2.91c-.36.24-.7.52-1 .82l-.86-.35c-.19-.09-.37-.05-.52.13-.14.15-.16.34-.04.51l.5.8c-.25.35-.45.72-.64 1.12l-.94-.04c-.21-.01-.37.1-.44.3-.07.2-.02.38.16.5l.73.59c-.1.41-.19.83-.22 1.27l-.89.29c-.21.07-.31.21-.31.42 0 .22.1.36.31.43l.89.28c.03.44.1.87.22 1.27l-.73.58c-.17.12-.22.31-.16.51.07.2.23.31.44.29l.94-.05c.18.39.39.77.63 1.12l-.5.8c-.12.18-.1.37.04.52.16.18.33.22.52.14l.86-.36c.3.31.64.58.99.82l-.2.92c-.04.22.03.39.2.49.2.1.38.08.54-.07l.69-.61c.39.17.8.33 1.21.44l.13.93c.03.21.16.35.37.39.22.03.39-.06.47-.24l.44-.84c.23.02.44.04.66.04z"
                                                            fill="#818b90"></path>
                                                    </svg></span></span></strong> y selecciona <strong>Dispositivos
                                            vinculados</strong></span>
                                </li>
                                <li class="QtrYx">Cuando se active la cámara, apunta tu teléfono hacia esta pantalla para
                                    escanear el código
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-6 text-center">
                            <p>
                                <img id="qr_code" style="width: 100%" class="img-fluid" alt="">
                            </p>
                            <p class="lead">
                                <a id="reload" href="#"
                                    class="btn btn-lg btn-secondary fw-bold border-black bg-white text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                        <path
                                            d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                    </svg>
                                </a>
                            <figcaption class="blockquote-footer">
                                Clic <cite title="Source Title">para actualizar el código QR</cite>
                            </figcaption>
                            </p>
                        </div>
                    </div>
                </div>               
            </main>
        </div>
      </div>      
    </div>
  </div>
  <input type="text" class="col-md-3 input-group-text input-group input-group-pretend mb-1 form-control" />

</div>
    <!-- End Modal Qr  -->
</div>
{{-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

$(document).on('click', '#crear_staticBackdrop_dialog', function(e) {

    e.preventDefault();

    var preguntas = $('#question .input-group:last');

    var nuevaPre = preguntas.clone();
    nuevaPre.find('input').val("");
    var indicePreguntas = parseInt(nuevaPre.attr('data-elemento'))+1;
    nuevaPre.attr('data-elemento',indicePreguntas);
    console.log(indicePreguntas);
    $('#question').append(nuevaPre);
    
    var respuestas = $('#asnwer .input-group:last');

    var nuevaRes = respuestas.clone();
    nuevaRes.find('input').val("");
    var indiceRespuestas = parseInt(nuevaRes.attr('data-elemento'))+1;
    nuevaRes.attr('data-elemento',indiceRespuestas);
    $('#asnwer').append(nuevaRes);

    var boton = $('#delete .eliminar:last');
    var botonclon = boton.clone();


    var indice = parseInt(botonclon.attr('data-elemento'))+1;
    botonclon.attr('data-elemento',indice);
    $('#delete').append(botonclon);

});

$(document).on('click', '.eliminar', function(e) {

    e.preventDefault();
    
    var dato=$(this).data('elemento');

    console.log(dato);
    
    $(this).remove();
    
    var preguntaBorrar = $('#question .input-group');
    var resBorrar = $('#asnwer .input-group');
    
    preguntaBorrar.map(function(i, e) {
        if(i == dato){
            $(this).remove();
        }
        });
    resBorrar.map(function(i, e) {
            if(i == dato){
            $(this).remove();
        }
        });

});

</script> --}}
