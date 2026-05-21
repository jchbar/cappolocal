var xmlhttp;


function activar(totalche) {
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
	var neto= totalche;
	//var oculto=document.getElementById("depositos").value;
    //alert ("("+oculto+")"); 
	//alert('total registros'+selIndex);
	for(j=1; j <= selIndex; j++){
			var valor = eval("document.getElementById('cancelar"+(j)+"').checked");
		//	alert ("("+j+")"); 
			if (valor == true) {
		    	neto-=redondear(parseFloat(document.getElementById("cancelarh"+(j)).value),2);
             	document.getElementById("cheques").value=redondear(parseFloat(neto),2);
			    difcon=redondear(parseFloat(document.getElementById("cheques").value)+parseFloat(document.getElementById("depositos").value),2);
	document.getElementById("diferenciacon").value=redondear(parseFloat(difcon),2);	
	         //  alert ("'true'("+neto+")"); 
			}
			else {
				document.getElementById("cheques").value=parseFloat(neto);
				difcon=redondear(parseFloat(document.getElementById("cheques").value)+parseFloat(document.getElementById("depositos").value),2);
	document.getElementById("diferenciacon").value=redondear(parseFloat(difcon),2);	
			//	alert ("'else'("+neto+")"); 
			}
        }
	
		

/*
xmlhttp.send(null)
*/
}


function redondear(cantidad, decimales) {
var cantidad = parseFloat(cantidad);
var decimales = parseFloat(decimales);
decimales = (!decimales ? 2 : decimales);
return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);0
}
/*
function redondear(cantidad, decimales) {
{
var original=parseFloat(numero);
var result=Math.round(original*100)/100 ;
return result;
}
*/
function activard(totaldep) {
/*
xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
*/
	var selIndex = document.getElementById('j').value;
	var totalregistros=0;
	var neto=totaldep;
  //  alert('total registros'+selIndex);
	for(j=1; j <= selIndex; j++){
			var valor = eval("document.getElementById('cancelard"+(j)+"').checked");
		//	alert ("("+j+")"); 
			if (valor == true) {
		    		neto-=parseFloat(document.getElementById("cancelarhd"+(j)).value);
             	document.getElementById("depositos").value=parseFloat(neto);
				 difcon=parseFloat(document.getElementById("cheques").value)+parseFloat(document.getElementById("depositos").value);
	document.getElementById("diferenciacon").value=parseFloat(difcon);	
				 
	         //  alert ("'true'("+neto+")"); 
			}
			else {
				document.getElementById("depositos").value=parseFloat(neto);
				difcon=parseFloat(document.getElementById("cheques").value)+parseFloat(document.getElementById("depositos").value);
	document.getElementById("diferenciacon").value=parseFloat(difcon);	
			//	alert ("'else'("+neto+")"); 
			}
        }
   		difcon=parseFloat(document.getElementById("cheques").value)+parseFloat(document.getElementById("depositos").value);
	document.getElementById("diferenciacon").value=parseFloat(difcon);	
	
/*
xmlhttp.send(null)
*/
}
/*

function revisarmonto(registro) {
alert(registro);
	var selIndex = document.getElementById('registros').value;
	var visible=document.getElementById("cancelart"+registro).value;
	var oculto=document.getElementById("cancelarh"+(registro)).value;
//alert('valor visible '+ visible  + ' vlor oculto '+oculto);
var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelarh"+i).value);
		alert ('+oculto'); 
	document.getElementById("diferencia").value=parseFloat(neto);

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
*/
function calcular(){
	//alert ('hola'); 
	var oculto=document.getElementById("saldo_libros").value;
	var visible=document.getElementById("saldo_bancos").value;
    //alert ("("+oculto+")"); 
	if (parseFloat(visible) > 0) {
		neto=parseFloat(document.getElementById("saldo_bancos").value)-parseFloat(document.getElementById("saldo_libros").value);
		var result=Math.round(neto*100)/100 ;
	document.getElementById("diferencia").value=parseFloat(result);
	}
}
