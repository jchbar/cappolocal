var xmlhttp;


function activar() {
/*
xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
*/
	var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
    //alert('total registros'+selIndex);
	for(j=0; j < selIndex; j++){
			var valor = eval("document.getElementById('cancelar"+(j+1)+"').checked");
			if (valor == true) {
				document.getElementById("cancelart"+(j+1)).disabled=false;
			}
			else {
				document.getElementById("cancelart"+(j+1)).disabled=true;
				document.getElementById("cancelart"+(j+1)).value= 0;
			}
        }
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
	document.getElementById("montoprestamo").value=parseFloat(neto);
	if (neto<1)
		document.getElementById("continuar").disabled=true;


/*
xmlhttp.send(null)
*/
}


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
/*
		if (parseFloat(visible) > parseFloat(oculto)){
			document.getElementById("continuar").disabled=true;
			alert("El monto a cancelar no puede ser mayor al saldo actual"); return false; }
		else {
*/
		{
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
