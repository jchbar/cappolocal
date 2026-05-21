
function ajaxFunction() {
var xmlHttp;
try {
// Firefox, Opera 8.0+, Safari
xmlHttp=new XMLHttpRequest();
return xmlHttp;
} catch (e) {
// Internet Explorer
try {
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
return xmlHttp;
} catch (e) {
try {
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
return xmlHttp;
} catch (e) {
alert("Tu navegador no soporta AJAX!");
return false;
}}}
}

function Enviar(_pagina,capa) {
var ajax;
ajax = ajaxFunction();

ajax.open("POST", _pagina, true);

ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
ajax.onreadystatechange = function()
{

if (ajax.readyState == 4)
{
if (ajax.status==200)
{
document.getElementById(capa).innerHTML = ajax.responseText;

}}}
ajax.send(null);
}

function Cargarcontenido(_pagina,seccion,formid,capa) {
var ajax;
var Formulario = document.getElementById(formid);
var longitudFormulario = Formulario.elements.length;
var cadenaFormulario = "";
var sepCampos;
sepCampos = "";

for (var i=0; i <= Formulario.elements.length-1;i++) {
if(Formulario.elements.type == 'checkbox') {
if( Formulario.elements.checked ) {
cadenaFormulario += sepCampos+Formulario.elements.name+'='+encodeURI(Formulario.elements.value);
sepCampos="&";
}
} else {
cadenaFormulario += sepCampos+Formulario.elements.name+'='+encodeURI(Formulario.elements.value);
sepCampos="&";
}
} 


ajax = ajaxFunction();

ajax.open("POST", _pagina, true);

ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
ajax.onreadystatechange = function()
{

if (ajax.readyState==1)
{
document.getElementById(capa).innerHTML = "";
}
if (ajax.readyState == 4)
{
if (ajax.status==200)
{
document.getElementById(capa).innerHTML = ajax.responseText;

}}}
ajax.send(cadenaFormulario+"&"+seccion);
}