//*********************Scripts ApiRest bot******************************/
//*********************Daruin Herrera 2021-12**************************/

//Validatión de token and data
$(document).ready(function() {
    if (typeof localStorage.tokWhat !== 'undefined') {
        var iat = localStorage.getItem('iat');
        var exp = localStorage.getItem('exp');

        const date1 = new Date(iat * 1000);
        const date2 = new Date(exp * 1000);
        const date3 = new Date();

        var create = date1.getFullYear() + '-' + ("0" + (date1.getMonth() + 1)).slice(-2) + '-' + ("0" + date1.getDate()).slice(-2) + " " + ("0" + date1.getHours()).slice(-2) + ":" + ("0" + date1.getMinutes()).slice(-2);
        var expires = date2.getFullYear() + '-' + ("0" + (date2.getMonth() + 1)).slice(-2) + '-' + ("0" + date2.getDate()).slice(-2) + " " + ("0" + date2.getHours()).slice(-2) + ":" + ("0" + date2.getMinutes()).slice(-2);
        var now = date3.getFullYear() + '-' + ("0" + (date3.getMonth() + 1)).slice(-2) + '-' + ("0" + date3.getDate()).slice(-2) + " " + ("0" + date3.getHours()).slice(-2) + ":" + ("0" + date3.getMinutes()).slice(-2);

        var fecha1 = moment(now);
        var fecha2 = moment(expires);

        var dif = fecha2.diff(fecha1, 'hours');
        localStorage.setItem('timeTk', dif);

        if (dif > 1) {
            $('#timeV').html('<p class="font-monospace pt-2"><i class="fa fa-check-square" aria-hidden="true" style="color: #2dce89;"></i> <small>Activo</small></p>');
            //Get data session  
            if(!$('#whatsapp_phone').val())
            {
                console.log('Waiting for data for the bot 🤖');
            }
            else
            {
                localStorage.removeItem('sessionData');
                if (valPhone($('#whatsapp_phone').val()) == false)
                {             
                    setInterval('valSession()',(10000*6));               
                }
            }                
            //
        } else {
            $('#timeV').html('<p class="font-monospace pt-2"><i class="fa fa-window-close" aria-hidden="true" style="color: #fb6340;"></i> <small>Expiro</small></p>');
            $('#cont-opt').attr('style', 'display:none');
            $('#cont-opt').attr('class', '');
        }

        ///console.log(date1);
        $('#iat p').html('Creado ' + create);
        $('#exp p').html('Expira ' + expires);
    } else {
        $('#cont-opt').attr('style', 'display:none');
        $('#cont-opt').attr('class', '');
        $('#iat p').removeClass('pt-2');
        $('#exp p').removeClass('pt-2');
        $('#iat p').html('<span>No tiene un token creado en este equipo</span>');
        $('#exp p').html('<span>Haga clic en el boton para generar un token de sesión</span>');
    }
    
});

//Generation of token for commerces 2021-12-07
$('#genToken').click(function() {    
    $('#genToken').attr('href', '#');
    if (valPhone($('#whatsapp_phone').val()) == false) {        
        var timeTk = localStorage.getItem('timeTk');
        if (timeTk < 1) {
            //storage.clear();            
            var keyWhatsApi = $('#keyWhatsApi').val();
            var whatsapp_phone = $('#whatsapp_phone').val().split('+')[1];            
            loginApi(whatsapp_phone, keyWhatsApi);
            location.reload();
        } else {
            alert('Su token aun esta vigente, vence en ' + timeTk + ' horas');
        }
    }
});
//Register commerce in the api
$('#waApi').click(function() {
    if (valPhone($('#whatsapp_phone').val()) == false) {
        let whatsapp_phone = $('#whatsapp_phone').val().split('+')[1];
        let address = $('#email_owner').val();
        let name = $('#name').val();
        let company = $(location).attr('hostname');

        if (!whatsapp_phone || !address || !name) {
            $("#alertWhats p").append("<br><kbd>Datos faltanes</kbd>");
            $("#alertWhats ul").append(
                $("<li>").text($('#whatsapp_phone').attr('placeholder')),
                $("<li>").text($('#address').attr('placeholder')),
                $("<li>").text($('#name').attr('placeholder'))
            );
            $('#alertWhats').attr('style', '');
        } else {
            var dataComer = {
                "company": company,
                "name": name,
                "whastappPhone": whatsapp_phone,
                "email": address
            };

            var urlApi = "https://phpstack-187120-2295101.cloudwaysapps.com/api/shops";
            conectApi(dataComer, urlApi, '', 'POST')
                .then(response => {
                    if (response.id == 409 || response.id == 500) {
                        console.log('Complete data, validating the existence of the trade..');
                        $("#alertWhats p").append('<br><kbd><i class="ni ni-spaceship"></i> Ya existe un comercio con estos datos!</kbd>');

                    } else {
                        localStorage.setItem('tokWhat', response.token);
                        localStorage.setItem('keyCommerc', response.keyShop);
                        localStorage.setItem('iat', response.iat);
                        localStorage.setItem('exp', response.exp);

                        $("#alertWhats").attr('class', 'alert alert-info');
                        $("#alertWhats ul").append(
                            $("<li>").text('Comercio registrado con exito!'),
                            $("<li>").text('Llave de comercio generada!'),
                            $("<li>").text("Token de api generado!")
                        );

                        $('#waApi').attr('style', 'display:none');
                        $('#regKey').attr('style', '');
                    }
                })
                .catch(error => {
                    console.log(error);
                    $("#alertWhats p").append("<br><kbd><pre>" + error + "</pre></kbd>");
                });
            $('#alertWhats').attr('style', '');
        }
    }
});
//Register key for commerces
$('#regKey').click(function() {
    var keyCommerc = localStorage.getItem("keyCommerc");
    $('#keyWhatsApi').val(keyCommerc);
    $('#restorant-form').submit();
});
//Get dialog
$('#dialog').click(function() {  
    if (valPhone($('#whatsapp_phone').val()) == false) {
        var whatsapp_phone = $('#whatsapp_phone').val().split('+')[1];
        var urlApi = "https://phpstack-187120-2295101.cloudwaysapps.com/api/shops/dialog/" + whatsapp_phone;
        var token = localStorage.getItem('tokWhat');
        var cantQuest = $('.quest').length;

        if (cantQuest == 0) {
            conectApi({}, urlApi, token, 'GET')
                .then(response => {
                    var control = response.length;
                    if (control > 1) {
                        response.forEach(function(element, i) {
                            $('#question').append('<div  class="input-group mb-1 quest" data-elemento="' + i + '"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="ni ni-circle-08"></i></span></div ><input type="text"  value ="' + element['pregunta'] + '" class="form-control" placeholder="Pregunta" aria-label="Pregunta" aria-describedby="basic-addon1"></div>');
                            $('#asnwer').append('<div class="input-group mb-1" data-elemento="' + i + '"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="ni ni-spaceship"></i></span></div ><input type="text"  value ="' + element['respuesta'] + '" class="form-control" placeholder="Respuesta" aria-label="Respuesta" aria-describedby="basic-addon1"></div>');
                            $('#delete').append('<button type="button" class="btn btn-danger eliminar"  data-elemento="' + i + '" style="margin-bottom:0.45rem !important; font-weight: bold;">-</button>');
                        });
                    } else {
                        response.forEach(function(element, i) {
                            $('#question').append('<div  class="input-group mb-1 quest" data-elemento="' + i + '"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="ni ni-circle-08"></i></span></div ><input type="text"  value ="' + element['pregunta'] + '" class="form-control" placeholder="Pregunta" aria-label="Pregunta" aria-describedby="basic-addon1"></div>');
                            $('#asnwer').append('<div class="input-group mb-1" data-elemento="' + i + '"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="ni ni-spaceship"></i></span></div ><input type="text"  value ="' + element['respuesta'] + '" class="form-control" placeholder="Respuesta" aria-label="Respuesta" aria-describedby="basic-addon1"></div>');
                            $('#delete').append('<button type="button" class="btn btn-danger eliminar" hidden="hidden"  data-elemento="' + i + '" style="margin-bottom:0.45rem !important; font-weight: bold;">-</button>');
                        });
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
});
//Create sessión for whatsappPhone
$('#qr').click(function() {
    if (valPhone($('#whatsapp_phone').val()) == false) {
        var token = localStorage.getItem('tokWhat');
        var whastappPhone = $('#whatsapp_phone').val().split('+')[1];
        var dataComer = {
            "whastappPhone": whastappPhone,
            "keyShop": $('#keyWhatsApi').val()
        };
        var urlApi = 'https://phpstack-187120-2295101.cloudwaysapps.com/api/shops/run';

        conectApi(dataComer, urlApi, token, 'POST')
            .then(response => {
                console.log(response);
                $('#qr_code').attr('src', 'https://phpstack-187120-2295101.cloudwaysapps.com' + response['prevImage']);
            })
            .catch(error => {
                console.log(error);
            });
    }

});
//Get qr and reload image
$('#reload').click(function() {
    if (valPhone($('#whatsapp_phone').val()) == false) {
        var whastappPhone = $('#whatsapp_phone').val().split('+')[1];
        var urlApi = 'https://phpstack-187120-2295101.cloudwaysapps.com/api/shops/getQr';
        var token = localStorage.getItem('tokWhat');
        var dataComer = {
            'whastappPhone': whastappPhone
        }

        conectApi(dataComer, urlApi, token, 'POST')
            .then(response => {
                $('#qr_code').attr('src', 'https://phpstack-187120-2295101.cloudwaysapps.com' + response['qrcode']);
            })
            .catch(err => {
                console.log(err);
            });
    }
});
//Update dialog
$(document).on('click', '#actualizar_staticBackdrop_dialog', function() {

    if (valPhone($('#whatsapp_phone').val()) == false) {
        var whastappPhone = $('#whatsapp_phone').val().split('+')[1];
        var urlApi = "https://phpstack-187120-2295101.cloudwaysapps.com/api/shops/dialog/";
        var token = localStorage.getItem('tokWhat');
        var preguntas = $('#question .input-group input');
        var respuestas = $('#asnwer .input-group input');
        var arrpreguntas = [];
        var arrresp = [];

        preguntas.map(function(i, e) {
            arrpreguntas[i] = $(this).val();
        });
        respuestas.map(function(i, e) {
            arrresp[i] = $(this).val();
        });

        // console.log(arrp);
        var dialogos = [];
        for (let index = 0; index < arrpreguntas.length; index++) {
            dialogos.push({ 'pregunta': arrpreguntas[index], 'respuesta': arrresp[index] });
        }

        console.log(dialogos);

        dataComer = {
            "whastappPhone": whastappPhone,
            "newDialog": dialogos
        };

        console.log(dataComer);

        conectApi(dataComer, urlApi, token, 'POST')
            .then(response => {
                console.log(response);
            })
            .catch(err => {
                console.log(err);
            });
    }
});


//**************Funtions generals**********************/
var cont=1
function valSession(){ 
    if($('#whatsapp_phone').val())
    {
        getDataSession($('#whatsapp_phone').val().split('+')[1]);            
        var datSes= JSON.parse(localStorage.getItem('sessionData'));
        if(datSes!==null)
        { 
            if(datSes['session']['state']=='CONNECTED')
                {
                    $('#scan_qr').attr('style','display:none');
                    $('#det_ses').attr('style','');
                    $('#spinerVal').attr('style','display:none');
                }
            else
                {
                    $('#scan_qr').attr('style','');
                    $('#det_ses').attr('style','display:none');
                    $('#spinerVal').attr('style','display:none');
                }	
            $('#battery').html(datSes['session']['battery']);
            $('#state').html(datSes['session']['state']);
            
            const date1 = new Date(datSes['session']['date'] * 1000);
            var fechaSes = date1.getFullYear() + '-' + ("0" + (date1.getMonth() + 1)).slice(-2) + '-' + ("0" + date1.getDate()).slice(-2) + " " + ("0" + date1.getHours()).slice(-2) + ":" + ("0" + date1.getMinutes()).slice(-2);
            $('#dateSes').html(fechaSes);

        }        
    }   
    cont++;
}
//Get data session
function getDataSession(whatsappPhone)
{
    var urlApi = "https://phpstack-187120-2295101.cloudwaysapps.com/api/shops/getState";
    var token = localStorage.getItem('tokWhat');
    dataComer = {    
    "whastappPhone": whatsappPhone
    };
    conectApi(dataComer,urlApi,token,'POST')
    .then(response => {
        localStorage.setItem('sessionData', JSON.stringify(response));
    })
    .catch(err => console.log(err));
}
//Validate phone
function valPhone(whatsappPhone) {
    const regex = /^[0-9]*$/;
    const respoNumbers = regex.test(whatsappPhone);
    if (respoNumbers == true && whatsappPhone.length <= 12) {
        alert('El número de whatsapp no cumple con las condiciones necesarias!');
        return true;
    } else {
        return false;
    }
}
//Generation de token
function loginApi(whastappPhone, keyWhatsApi) {
    var urlApi = "https://phpstack-187120-2295101.cloudwaysapps.com/api/shops/login";
    dataComer = {
        "whastappPhone": whastappPhone,
        "keyShop": keyWhatsApi
    };
    //alert('iniciando conección...');
    conectApi(dataComer, urlApi, '', 'POST')
        .then(response => {
            localStorage.setItem('tokWhat', response.token);
            localStorage.setItem('iat', response.iat);
            localStorage.setItem('exp', response.exp);
        })
        .catch(err => console.log(err));
}
//Connection con ApiRest
function conectApi(dataComer, urlApi, tokenApi, method) {
    return new Promise((resolve, reject) => {
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        myHeaders.append("Authorization", "Bearer " + tokenApi);

        var raw = JSON.stringify(dataComer);
        if (method == 'GET') {
            var requestOptions = {
                method: method,
                headers: myHeaders,
                redirect: 'follow'
            };
        } else {
            var requestOptions = {
                method: method,
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
        }
        fetch(urlApi, requestOptions)
            .then(response => {
                return response.json();
            })
            .then(result => {
                console.log(result);
                resolve(result);
            })
            .catch(error => {
                console.log('error', error);
                reject(error);
            });
    });
}