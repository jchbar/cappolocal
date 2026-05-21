//---------------------------------------------------------------------------------------------
//Funciones para el calendario
//---------------------------------------------------------------------------------------------
var NS7      =(document.getElementById && !document.all)?1:0;
var losMeses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio",											"Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var losDias  = new Array (31,28,31,30,31,30,31,31,30,31,30,31);
var losDiasDeLaSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sabado");
var diasSemana  = new Array ('L','M','X','J','V','S','D');
var idioma      = "es";

/*****************************************************************************/
function ReconoceCapas(capitas){
 var cadena="";
    if (NS7)  {
          cadena=capitas+"=document.getElementById('"+capitas+"')";
	  eval (cadena);
    }
}

/*****************************************************************************/
function ReconoceCapasArray(capitas){
 var cadena="";
    if (NS7)  {
    	for(var i=0;i<capitas.length;i++){
          cadena=capitas[i]+"=document.getElementById('"+capitas[i]+"')";
	  eval (cadena);
	 }
    }
}
/*****************************************************************************/
function explode(elSeparador,laCadena){
	var elArray = new Array();
	var cadenita = "";
	for (var a=0,indice=0;a<laCadena.length;a++){
	    if (laCadena.charAt(a)==elSeparador){
	        elArray[indice] = cadenita;
	        indice++;
	        cadenita = "";
	    }else{
	        cadenita+=laCadena.charAt(a);
	    }
	}//for a
	elArray[indice] = cadenita;
	return elArray;
}//explode
/*****************************************************************************/
function implode(elSeparador,elArray){
	var cadena = elArray[0];
	for (var a=1;a<elArray.length;a++){
		cadena = cadena + elSeparador + elArray[a];
	}//for a
	return cadena;
}//implode
/*****************************************************************************/
/*****************************************************************************/
var hoy = new Date();
var diaHoy = hoy.getDate();
var mesHoy = hoy.getMonth()+1;
var anoHoy = hoy.getYear();
if (anoHoy<1900) anoHoy+=1900;
var elDia = diaHoy;
var elMes = mesHoy;
var elAno = anoHoy;
/*****************************************************************************/
/*****************************************************************************/
function siguienteMes(mes,ano){
	if (mes==12){
	    mes = 1;
	    ano++;
	}else{
	    mes++;
	}
	return mes+"-"+ano;
}//siguienteMes
/*****************************************************************************/
function anteriorMes(mes,ano){
	if (mes==1){
	    mes = 12;
	    ano--;
	}else{
	    mes--;
	}
	return mes+"-"+ano;
}//anteriorMes
/*****************************************************************************/
function colorear(dia,mes,ano,esDomingo){
	var hoy = new Date();

   	  var diaHoy = hoy.getDate();
 	  var mesHoy = hoy.getMonth()+1;
	  var anoHoy = hoy.getYear();



	if (anoHoy<1900) anoHoy+=1900;
	var celdaDia;
	if ((ano>anoHoy) || ((ano==anoHoy) && (mes>mesHoy)) || ((ano==anoHoy) && (mes==mesHoy) && (dia>=diaHoy))){
		if ((dia+"-"+mes+"-"+ano)==(diaHoy+"-"+mesHoy+"-"+anoHoy))
		    celdaDia = "<td id=\"celda"+dia+"\" style=\"background-color:#000066;color:#FFFFFF;font-weight:bold;cursor:pointer;font-family: Verdana, Arial, Helvetica; font-size: 9px;\" onClick=\"marcar("+dia+","+mes+","+ano+");\" width=\"12\" height=\"12\">"+dia+"</td>";
		else{
		    if (esDomingo)
		        celdaDia = "<td id=\"celda"+dia+"\" style=\"background-color:#FFFFFF;color:#CC0000;cursor:pointer;font-family: Verdana, Arial, Helvetica; font-size: 9px;\" onClick=\"marcar("+dia+","+mes+","+ano+");\" width=\"12\" height=\"12\">"+dia+"</td>";
			else
				celdaDia = "<td id=\"celda"+dia+"\" style=\"background-color:#FFFFFF;color:#000000;cursor:pointer;font-family: Verdana, Arial, Helvetica; font-size: 9px;\" onClick=\"marcar("+dia+","+mes+","+ano+");\" width=\"12\" height=\"12\">"+dia+"</td>";
		}//else
	}else{
        celdaDia = "<td id=\"celda"+dia+"\" style=\"background-color:#FFFFFF;color:#000000;cursor:pointer;font-family: Verdana, Arial, Helvetica; font-size: 9px;\" onClick=\"marcar("+dia+","+mes+","+ano+");\" width=\"12\" height=\"12\">"+dia+"</td>";
		// no poder acceder a dias pasados ->celdaDia = "<td id=\"celda"+dia+"\" style=\"background-color:#aaaaaa;color:#888888;cursor:default;font-family: Verdana, Arial, Helvetica; font-size: 9px;\" width=\"12\" height=\"12\">"+dia+"</td>";
	}
	return celdaDia;
}//colorear
/*****************************************************************************/
function generarCalendario(mes,ano){
   	  var hoy    = new Date();
   	  var diaHoy = hoy.getDate();
 	  var mesHoy = hoy.getMonth()+1;
	  var anoHoy = hoy.getYear();
      if (anoHoy<1900) anoHoy +=1900;

  	  var dias=losDias[mes-1];
  	  var i,j;
      diaUno=calcularDia(1,mes,ano);
	  var calendarioMes="";
      calendarioMes=calendarioMes+"<table border='0' width=100 style=\"border:1px solid #000000;text-align:center;background-color:white;\"><tr>";
      var mesAnt = anteriorMes(mes,ano);
      mesAnt = explode("-",mesAnt);
      var mesSig = siguienteMes(mes,ano);
      mesSig = explode("-",mesSig);
      mesAnt = "onClick=\"mostrarCalendario('"+campo+"',"+mesAnt[0]+","+mesAnt[1]+")\"";
      calendarioMes+="<td width=\"12\" height=\"12\"><img src='imagenes/izquierda.jpg' alt='Mes Anterior' "+mesAnt+" style=\"cursor:pointer;width:12;height:12;\"/></td>";
      calendarioMes= calendarioMes + "<td colspan='4' class=\"tfecha\" style=\"cursor:default;\">"+losMeses[mes-1]+" "+ano+"</td>";
      calendarioMes+="<td width=\"12\" height=\"12\"><img src='imagenes/derecha.jpg' alt='Mes Siguiente' style=\"cursor:pointer;width:12px;height:12px;\" onClick=\"mostrarCalendario('"+campo+"',"+mesSig[0]+","+mesSig[1]+",'','')\"/></td>";
      calendarioMes+="<td><img src=\"imagenes/cerrar.png\" border=\"0\" style=\"cursor:pointer;width:12px;height:12px;\" onclick=\"cerrar('calendario');\" title=\"Cerrar calendario\"></td></tr>";
	  //rellenamos la segunda fila con las primeras letras de cada dia L M X J V S D
	  calendarioMes+="<tr style=\"background-image:url(imagenes/amarillo.gif);font-weight:bold;font-family: Verdana, Arial, Helvetica; font-size: 9px;\">";
	  for (i=0;i<7;i++)
		if (idioma=="es")
			calendarioMes=calendarioMes+ "<td class='semana'  width=\"12\" height=\"12\" style=\"cursor:default;\">"+diasSemana[i]+"</td>";
		else
			calendarioMes=calendarioMes+ "<td class='semana'  width=\"12\" height=\"12\" style=\"cursor:default;\">"+diasSemanaEn[i]+"</td>";
	calendarioMes=calendarioMes+"</tr>";
  	calendarioMes+="<tr>";
  	//rellenamos hasta el primer dia de Mes a guiones
  	for (i=0;diasSemana[i]!=diaUno;i++)
  			calendarioMes=calendarioMes+"<td style=\"background-color:#aaaaaa;color:#666666;\"  width=\"12\" height=\"12\" style=\"cursor:default;\">-</td>";
  	dias_del_mes = losDias[mes-1];
  	if ((mes==2) && (ano % 4==0)) dias_del_mes++;  //es bisiesto
  	for (j=i,i=1;i<=dias_del_mes;){
		for (;j<7 && i<=dias_del_mes;j++,i++)
		if (j==6){
			calendarioMes+=colorear(i,mes,ano,1);
		}else
			calendarioMes+=colorear(i,mes,ano,0);
		if (j==7){
			calendarioMes+=("</tr><tr>");
			j=0;
		}//if (j==7)
	}//1º for
	if (j>0)
  	for (;j<7;j++)
  		calendarioMes+="<td style=\"background-color:#aaaaaa;color:#666666;\" width=\"12\" height=\"12\" style=\"cursor:default;\">-</td>";
  	calendarioMes+= "</tr></table>"
  	return calendarioMes;
}//dameCalendario
/*****************************************************************************/
function calcularDia(dia,mes,ano){
	var dias=365*(ano-1);
	var i;
	var deSemana;
       for (i=0;i<(mes-1);i++)
		dias+=losDias[i];
  	dias+=dia-1;
  	dias+=(Math.floor((ano-1)/4));
  	if ((mes>2) && (((ano%4)==0)))
  		 dias++;
  	deSemana=dias % 7;
    if (idioma=="es"){
	  	if (deSemana==0) deSemana=6;
	  	else deSemana--;
  	}
   	return diasSemana[deSemana];
}//calcularDia

/*****************************************************************************/
function mostrarCalendario(campito,mes,ano){
	
	campo = campito;
	alert ("+ano+");
   var tablaCalendario = "<table>";
	tablaCalendario+= "<tr><td>"+generarCalendario(mes,ano)+"</td></tr></table>";
	var celda;

	calendario.innerHTML = tablaCalendario;
	abrirCalendario('calendario');
	

}//mostrarCalendario
/*****************************************************************************/
function marcar(dia,mes,ano){
	if ((elDia<10) && (elDia.toString().length)>1){
	    elDia = elDia.toString().substr(1,1);
	}
	elDia = dia;
	elMes = mes;
	elAno = ano;
	devolverFecha(dia,mes,ano);
}//marcar
/*****************************************************************************/
function devolverFecha(elDia,elMes,elAno){
	if (elDia<10) elDia = "0"+elDia;
	if (elMes<10) elMes = "0"+elMes;
	var cadena="document.formulario."+campo+".value='"+elDia+"/"+elMes+"/"+elAno+"'";
    	eval(cadena);
	cerrarCapa('calendario');
}
/*****************************************************************************/
function abrirCalendario(laCapa){
	eval(laCapa+".style.visibility='visible'");
	//alert(document.getElementById(laCapa)+".style.visibility");
	//eval("document.getElementById('"+laCapa+"').style.visibility='visible'");
}//abrirCapa
/*****************************************************************************/
function abrirCapa(laCapa){

	eval(laCapa+".style.visibility='visible'");
	
}//abrirCapa
/*****************************************************************************/

function cerrarCapa(laCapa){
	calendario.innerHTML = "";
	eval(laCapa+".style.visibility='hidden'");
}
/*****************************************************************************/
function cerrar(laCapa){
    cerrarCapa('calendario');
}
/*****************************************************************************/