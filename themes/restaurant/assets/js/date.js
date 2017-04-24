/*
FECHA:

getDay() -> Retorna el día de la semana a partir de un dígito del 0 al 6
getDate() -> Retorna el día del mes a partir de un dígito del 1 al 31
getMonth() -> Retorna el mes del año a partir de un dígito del 0 al 11
getFullYear() -> Retorna el año con 4 dígitos

*/

var fecha = new Date();

var dia_semana = [
"Domingo",
"Lunes",
"Martes",
"Miércoles",
"Jueves",
"Viernes",
"Sábado"
];

var mes = [
"Enero",
"Febrero",
"Marzo",
"Abril",
"Mayo",
"Junio",
"Julio",
"Agosto",
"Septiembre",
"Octubre",
"Noviembre",
"Diciembre"
];

/*var dame_fecha = "Hoy " + dia_semana[fecha.getDay()] + ", " + fecha.getDate() + " de " + mes[fecha.getMonth()] + " del " + fecha.getFullYear();
document.write("<p>" + dame_fecha + "</p>");*/

/*
HORA:

getHours -> retorna la hora
getMinutes -> retorna los minutos
getSeconds -> retorna los segundos

*/
/*
var hora = new Date();
var dame_hora = hora.getHours() + ":" + hora.getMinutes() + ":" + hora.getSeconds();
document.write("<p>" + dame_hora + "</p>");*/