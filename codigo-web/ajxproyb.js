var xmlhttp;

function ajax2call(sel2) {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
//	var selIndex = sel2.options[sel2.selectedIndex].value;
	var valor = document.getElementById('lascuotas').value;
//	alert(valor);
//	alert(document.getElementById('montoprestamo').value);

	var linea='ajaxWork.php?montoprestamo=' + 
					(document.getElementById('montoprestamo').value) +
						'&num_cuotas=' + valor +
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=12' +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1';
						// +  document.getElementById('monpre_sdp').value;

//	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}
/*	alert('llegue');
http://localhost/caja/ajaxWork.php?montoprestamo=1234&num_cuotas=12&p_interes=10&divisible=12&tipo_interes=Amortizada&f_ajax=cal2int&cual=1
}
*/


function cambio2() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
//	document.getElementById("cancelados").value= xmlDoc.getElementsByTagName("cancelados")[0].childNodes[0].nodeValue;
	document.getElementById("netoarecibir").value= xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("interesdiferido").value= xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("descuentosadm").value= xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
//	document.getElementById("marcados").value= xmlDoc.getElementsByTagName("marcados")[0].childNodes[0].nodeValue;
//	document.getElementById("reintegros").value= xmlDoc.getElementsByTagName("reintegros")[0].childNodes[0].nodeValue;
	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	if (document.getElementById("netoarecibir").value <= 0)
		alert('Verifique los datos. El neto a recibir no puede ser menor o igual a cero');
	}
}

*/

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

