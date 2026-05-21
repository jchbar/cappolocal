var xmlhttp;


function activar() {
//	alert('llegue');
/*
xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
*/
/*
var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
    //alert('total registros'+selIndex);
	for(j=0; j < selIndex; j++){
*/
//		alert ('valor a');
		var valor = eval("document.getElementById('nominasnormales').checked");
//		alert ('valor b');

		if (valor == false) // {
				document.getElementById("inputString2").disabled=false;
//				alert('pase true');
//			}
			else // {
				document.getElementById("inputString2").disabled=true;
//				alert('pase false');
//			}
//        }

/*
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
	document.getElementById("montoprestamo").value=parseFloat(neto);
	if (neto<1)
		document.getElementById("continuar").disabled=true;
*/

/*
xmlhttp.send(null)
*/
}

/*
function revisarmonto(registro) {
//alert(registro);
	var selIndex = document.getElementById('registros').value;
	var visible=document.getElementById("cancelart"+registro).value;
	var oculto=document.getElementById("cancelarh"+(registro)).value;
    //alert('valor visible '+visible + ' vlor oculto '+oculto);

var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
	document.getElementById("montoprestamo").value=parseFloat(neto);

	if (parseFloat(visible) <= 0) {
		document.getElementById("continuar").disabled=true;
		alert("El monto a cancelar no puede ser menor o igual a 0"); return false; }
	else 
		if (parseFloat(visible) > parseFloat(oculto)){
			document.getElementById("continuar").disabled=true;
			alert("El monto a cancelar no puede ser mayor al saldo actual"); return false; }
		else {
			document.getElementById("continuar").disabled=false;
			return true;
		}
}

function calcular(){
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
	document.getElementById("montoprestamo").value=parseFloat(neto);
}
*/
