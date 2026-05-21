var xmlhttp;

function relacion_ahorro() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var selIndexr= document.getElementById('registrosr').value;
	var linea='ajaxahorro.php?registros=' + selIndexr+'&';
	var otralinea='';
//	alert(linea);
	var totalregistrosr = totalregistrosa = 0;
	for(j=0; j < selIndexr; j++){			
			var valor = eval("document.getElementById('retencion"+(j+1)+"').checked");
//			alert(valor2);
			if (valor == true) {
				
				totalregistrosr++;
				var valor2 = eval("document.getElementById('retencion"+(j+1)+"').value");
				otralinea=otralinea+'retencion'+(totalregistrosr)+'='+valor2+'&'
			}
        }
//		alert(otralinea);
	linea=linea+otralinea+'totalregistrosr='+totalregistrosr+'&';
	var otralinea='';
	var selIndexa= document.getElementById('registrosa').value;
	for(j=0; j < selIndexa; j++){			
			var valor = eval("document.getElementById('aporte"+(j+1)+"').checked");
//			alert(valor2);
			if (valor == true) {
				
				totalregistrosa++;
				var valor2 = eval("document.getElementById('aporte"+(j+1)+"').value");
				otralinea=otralinea+'aporte'+(totalregistrosa)+'='+valor2+'&'
			}
        }
//		alert(otralinea);

	linea=linea+otralinea+'totalregistrosa='+totalregistrosa+'&';
//	alert(linea);


	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
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

function cambio2() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("totalnominasocio").value= xmlDoc.getElementsByTagName("totalnominasocio")[0].childNodes[0].nodeValue;
	document.getElementById("totalregistrosocio").value= xmlDoc.getElementsByTagName("totalregistrosocio")[0].childNodes[0].nodeValue;
	document.getElementById("totalnominaucla").value= xmlDoc.getElementsByTagName("totalnominaucla")[0].childNodes[0].nodeValue;
	document.getElementById("totalregistroucla").value= xmlDoc.getElementsByTagName("totalregistroucla")[0].childNodes[0].nodeValue;
	
	document.getElementById("totalnominas").value= xmlDoc.getElementsByTagName("totalnominas")[0].childNodes[0].nodeValue;
	document.getElementById("totalregistros").value= xmlDoc.getElementsByTagName("totalregistros")[0].childNodes[0].nodeValue;
/*
document.getElementById("netoarecibir").value= xmlDoc.getElementsByTagName("neto")[0].childNodes[0].nodeValue;
	document.getElementById("montoprestamo").value= xmlDoc.getElementsByTagName("montoprestamo")[0].childNodes[0].nodeValue;
	document.getElementById("descuentosadm").value= xmlDoc.getElementsByTagName("descuentosadm")[0].childNodes[0].nodeValue;
	document.getElementById("marcados").value= xmlDoc.getElementsByTagName("marcados")[0].childNodes[0].nodeValue;
	document.getElementById("reintegros").value= xmlDoc.getElementsByTagName("reintegros")[0].childNodes[0].nodeValue;
	if (document.getElementById("netoarecibir").value <= 0)
		alert('Verifique los datos. El neto a recibir no puede ser menor o igual a cero');
//	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
//	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
//	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
*/
}
}

