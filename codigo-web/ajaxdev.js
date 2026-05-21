var xmlhttp=false;

// if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
//  xmlhttp = new XMLHttpRequest();
// }

/*
function ajax_call() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
//	var selIndex = document.getElementById('lascuotas').options[document.getElementById('lascuotas').selectedIndex].value;
//	var selIndex = document.getElementById("lascuotas").options[document.getElementById("lascuotas").selectedIndex].value; 
	var selIndex = document.getElementById('lascuotas').value;
//	comboValue = document.getElementById('lascuotas').options[selIndex].value;
	var linea='ajaxWork.php?montoprestamo=' + 
					(document.getElementById('monpre_sdp').value-document.getElementById('inicial').value) + 
						'&num_cuotas=' + selIndex + 
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=' + document.getElementById('factor_division').value +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1';
						// +  document.getElementById('monpre_sdp').value;
						// document.getElementById('lascuotas');
// 	alert(linea);
	xmlhttp.onreadystatechange=cambio;
	xmlhttp.open("GET", linea, true);
//----------
//----------
	xmlhttp.send(null)
//	return false;
}


function cambio() {
if (xmlhttp.readyState==4) {
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
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


function cambio2() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("cancelados").value= xmlDoc.getElementsByTagName("cancelados")[0].childNodes[0].nodeValue;
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
	}
}

}
*/

function calccanc(codigo, cuantos, devuelto) {
/*
	xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
*/
//	var selIndex = document.getElementById(cuantos).value;

	var selIndex = cuantos; // eval("document.getElementById('cont_"+(codigo)+"').value");
//	var linea='ajaxCalc2.php?registros=' + selIndex+'&';
	var otralinea='';
	var registrostotales = eval("document.getElementById('registrostotales').checked");
	
	var totalregistros=0;
	var eltotal=0;
	for(j=0; j < selIndex; j++){
//			var valor='cancelar'+(j+1);
//			alert('valor ='+valor);
			
//			var indice = 1;
//			eval("var variable" + indice + " = 'valor'");
			
//			alert('l1');
//			alert("document.getElementById('"+codigo+'_'+(j+1)+"')");
			var valor = eval("document.getElementById(\""+codigo+'_'+(j+1)+"\").checked");
//			var valor = eval("document.getElementById(\""+codigo+(j+1)+"\").checked");
//			var valor = document.getElementById("C00123_1").value;
/*
			alert(valor);
			var valor2 = eval("document.getElementById("+codigo+'_'+(j+1)+").value");
			alert(valor2);
*/
			if (valor == true) {
//				alert('entre');
				totalregistros++;
				var valor2 = eval("document.getElementById('V"+codigo+'_'+(j+1)+"').value");
				valor2 = parseFloat(valor2);
				var valor3=(Math.round(valor2*100)/100);
				eltotal+=valor3;
//				alert(eltotal);
//				otralinea=otralinea+'cancelar'+(j+1)+'='+valor2+'&'
//				otralinea=otralinea+'codigo'+(totalregistros)+'='+valor2+'&'
			}
        }
		document.getElementById("D"+codigo).value= eltotal;
		document.getElementById("R"+codigo).value= devuelto - eltotal;
//	for(j=0; j < selIndex; j++){
//		document.getElementById("totalgeneral").value= eltotal;

	/*
					(document.getElementById('monpre_sdp').value-document.getElementById('inicial').value) + 
						'&num_cuotas=' + selIndex + 
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=' + document.getElementById('factor_division').value +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1';
						*/
//	linea=linea+otralinea+'totalregistros='+totalregistros+'&micedula='+document.getElementById('micedula').value+'&montoprestamo=';
//	linea+=document.getElementById('montoprestamo').value;
// 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

