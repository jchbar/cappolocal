var xmlhttp;

function ajax_call(sel) {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var comboValue = sel.options[sel.selectedIndex].value;
	var linea='saquecuotas.php?tipo='+comboValue;
//	alert(linea);

	xmlhttp.onreadystatechange=cambio;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
//	return false;
}


function cambio() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("lascuotas").value= xmlDoc.getElementsByTagName("ncuota")[0].childNodes[0].nodeValue;
	lacuota=xmlDoc.getElementsByTagName("ncuota")[0].childNodes[0].nodeValue;
//	alert(lacuota);
	document.getElementById("lascuotas").options.length=0;
	document.getElementById("lascuotas").options[document.getElementById("lascuotas").options.length]=new Option('',0);
	for (i=lacuota;i>0;i--)
		document.getElementById("lascuotas").options[document.getElementById("lascuotas").options.length]=new Option(i,i);
	document.getElementById("interes_sd").value=xmlDoc.getElementsByTagName("interes_sd")[0].childNodes[0].nodeValue;
	document.getElementById("tipo_interes").value=xmlDoc.getElementsByTagName("tipo_interes")[0].childNodes[0].nodeValue;
	document.getElementById("calculo").value=xmlDoc.getElementsByTagName("calculo")[0].childNodes[0].nodeValue;
}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
//	  alert('a');
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
//	  alert('b');
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

/*
function GetXmlHttpObject2()
{
if (window.XMLHttpRequest)
  {
//	  alert('a');
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
//	  alert('b');
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

function ajax2call(sel2) {
	xmlhttp2=GetXmlHttpObject2();
	if (xmlhttp2==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var selIndex = sel2.options[sel2.selectedIndex].value;
	var linea='ajaxWork.php?montoprestamo=' + 
					(document.getElementById('montoprestamo').value + 
						'&num_cuotas=' + selIndex + 
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=12' +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1';
						// +  document.getElementById('monpre_sdp').value;
	alert(selIndex);
	xmlhttp2.onreadystatechange=cambio2;
	xmlhttp2.open("GET", linea, true);
	xmlhttp2.send(null)
	alert('llegue');
}

function cambio2() {
if (xmlhttp2.readyState==4) {
	xmlDoc2=xmlhttp2.responseXML;
	document.getElementById("cancelados").value= xmlDoc2.getElementsByTagName("cancelados")[0].childNodes[0].nodeValue;
	document.getElementById("netoarecibir").value= xmlDoc2.getElementsByTagName("neto")[0].childNodes[0].nodeValue;
	document.getElementById("montoprestamo").value= xmlDoc2.getElementsByTagName("montoprestamo")[0].childNodes[0].nodeValue;
	document.getElementById("descuentosadm").value= xmlDoc2.getElementsByTagName("descuentosadm")[0].childNodes[0].nodeValue;
	document.getElementById("marcados").value= xmlDoc2.getElementsByTagName("marcados")[0].childNodes[0].nodeValue;
	document.getElementById("reintegros").value= xmlDoc2.getElementsByTagName("reintegros")[0].childNodes[0].nodeValue;
	document.getElementById("cuota").value= xmlDoc2.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	if (document.getElementById("netoarecibir").value <= 0)
		alert('Verifique los datos. El neto a recibir no puede ser menor o igual a cero');
	}
}


/*
function calccancel() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	alert('1');

	var data = document.getElementsByName( "cancelar[]" );
	if( data != null ){
		for(j=0; j < data.length; j++){
		// you have the data in the client for your array
//			eval(
//				 alert( data[j].innerHTML ) ; // ) ;
//			eval( alert( data[j].childNodes[0].nodeValue; ) ) ;
        }
      }

// arreglo=document.getElementsByName("cancelar[]");
	var linea='ajaxCalc.php?cancelar='+ document.getElementsById( "cancelar" );
//		document.getElementById("data");
//	var linea='ajaxCalc.php?';
//-----
//-----
 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
    xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
//	xmlhttp.setRequestHeader("Content-length", params.length);
//	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(null)
//	return false;
}

function calccanc() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var selIndex = document.getElementById('registros').value;
	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';
	
	var totalregistros=0;
	for(j=0; j < selIndex; j++){
//			var valor='cancelar'+(j+1);
//			alert('valor ='+valor);
			
//			var indice = 1;
//			eval("var variable" + indice + " = 'valor'");
			
			var valor = eval("document.getElementById('cancelar"+(j+1)+"').checked");
//			alert(valor2);
			if (valor == true) {
				
				totalregistros++;
				var valor2 = eval("document.getElementById('cancelar"+(j+1)+"').value");
//				otralinea=otralinea+'cancelar'+(j+1)+'='+valor2+'&'
				otralinea=otralinea+'cancelar'+(totalregistros)+'='+valor2+'&'
			}
        }
//			alert(otralinea);

	linea=linea+otralinea+'totalregistros='+totalregistros+'&micedula='+document.getElementById('micedula').value+'&montoprestamo=';
	linea+=document.getElementById('montoprestamo').value;
// 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

*/
/*
function GetInfoString(){
		var iCount =0;
	    var sData="";
//		iCount=document.getElementsByName("cancelar[]","").length;
		iCount=document.getElementsByName("cancelar[]","").length;
		alert('icount = '+iCount);
//		var checkboxes = document.getElementById("form1").cancelar;
//		alert(' cuantos '+checkboxes);
//		iCount=form1.elements.namedItem("cancelar[]","").length;
//		iCount=document.getElementyBy
//		iCount=myform.elements.namedItem("cancelar[]","").length;
	    if(iCount>>0){
	        for(i=0;i<iCount;i++) {
//	            if (myform.elements.namedItem("cancelar[]","")(i).checked){
//	            if (form1.elements.namedItem("cancelar[]","")(i).checked){
				var checkbox = document.getElementById("cancelar").checked;
				if (checkbox) { // (document.forms['form1'].elements[cancelar][i].checked){
					alert(i);

					sData += "&cancelar[]=" +  document.getElementsByName("cancelar[]","")(i).value;
//					sData += "&cancelar[]=" +  form1.elements.namedItem("cancelar[]","")(i).value;
//					sData += "&cancelar[]=" +  myform.elements.namedItem("cancelar[]","")(i).value;
				}
			}
	    }     
		alert(sData);
		return sData;
	}

*/

