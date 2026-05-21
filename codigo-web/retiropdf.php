<?php
/*  
  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
*/

session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}


define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

/*
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(50);
$pdf->SetX(0);
$pdf->MultiCell(220,6,"Planilla de Pre-Inscripción",0,C,0);//

$pdf->Ln();    
*/
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$elretiro=$_SESSION['elretiro'];
$lagestion=$_SESSION['lagestion'];
$sql = "select * from sgcaf710 where tipo='$elretiro'";
$query  = mysql_query($sql);
$retiro = mysql_fetch_array($query);
$eltitulo=trim($retiro['descri']).' ';
if ($lagestion == 'S') 
	if ($retiro['aprobar']==0)
		$eltitulo.=' (Solicitud)';
	else $eltitulo.=(($retiro['porcentaje']==100)?'(Solicitud)':'(Solicitud/Aprobación)');
else $eltitulo.=' (Aprobación)';
$pdf->MultiCell(0,0,$eltitulo,0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 

$pdf->SetFont('Arial','',10);
$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
$socio = mysql_fetch_assoc($query);
$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
$sqlretiro = "select * from sgcaf700 where cedsoc='$estacedula' order by fechareti desc limit 1";
$qry = mysql_query($sqlretiro);
$rretiro = mysql_fetch_assoc($qry);
if ($retiro['aprobar']==0) {
	$cuento= '   Yo, '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).' mayor de edad, titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' y Socio de esta institución identificado con el Código '.$socio['cod_prof'];
	$cuento.='me dirijo a la Junta Directiva con el fin de solicitar la cantidad de Bolívares '.strtoupper(num2letras($rretiro['montoreti']));
	$cuento.='  ****Bs. ('.trim(number_format($rretiro['montoreti'],2,".",",")).')**** en calidad de retiro de mis haberes de la Asociación. Dicha decisión obedece al (los) siguiente(s) motivo(s): ';
	$cuento.=trim($rretiro['motivo']).' con la(s) siguiente(s) observación(es) ' ;
	$cuento.=trim($rretiro['observa1']).' ';
	$cuento.=trim($rretiro['observa2']).' ';
	$cuento.=trim($rretiro['observa3']).' ';
}
else
{
	$cuento= '   Yo, '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).' mayor de edad, titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' y Socio de esta institución identificado con el Código '.$socio['cod_prof'];
	$cuento.='me dirijo a la Junta Directiva con el fin de solicitar la cantidad de Bolívares '.strtoupper(num2letras($rretiro['montoreti']));
	$cuento.='  ****Bs. ('.trim(number_format($rretiro['montoreti'],2,".",",")).')**** en calidad de retiro de mis haberes de la Asociación. Dicha decisión obedece al (los) siguiente(s) motivo(s): ';
	$cuento.=trim($rretiro['motivo']).' con la(s) siguiente(s) observación(es) ' ;
	$cuento.=trim($rretiro['observa1']).' ';
	$cuento.=trim($rretiro['observa2']).' ';
	$cuento.=trim($rretiro['observa3']).' ';
	$cuento.='De igual manera, en reunión de Junta Directiva según Acta N° ' . $rretiro['nro_acta'] . ' de fecha ' . convertir_fechadmy($rretiro['fecha_acta']).' se acordó la aprobación de un retiro de haberes al socio ';
	$cuento.= trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).', titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' identificado con el Código '.$socio['cod_prof'];
	$cuento.=' por la cantidad de Bolívares '.strtoupper(num2letras($rretiro['montoreti']));
	$cuento.='  ****Bs. ('.trim(number_format($rretiro['montoreti'],2,".",",")).')****. Dicha operacion será acreditada en la Cuenta ';
	$cuento.='Nro. '.$socio['ctan_prof'];
}
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->MultiCell(0,5,$cuento,0,'L'); 
$linea+=30;
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'______________________________________',0,0,'C',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Firma/Cédula del Asociado',0,0,'C',0);

$linea+=15;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Socio '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']),0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Cédula '.$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(140);
$pdf->Cell(60,6,'Código '.$socio['cod_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Descripción',1,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(30,6,'Monto',1,0,'C',0);
$pdf->SetX(110);
$pdf->Cell(30,6,'- Monto Retiro',1,0,'C',0);
$pdf->SetX(140);
$pdf->Cell(50,6,'Total Haberes',1,0,'C',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Ahorros Socio al '.convertir_fechadmy($socio['ultap_prof']),0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(30,6,number_format(($socio['hab_f_prof']+$rretiro['ret_capu']),2,".",","),0,0,'R',0);
$pdf->SetX(110);
$pdf->Cell(30,6,number_format($rretiro['ret_capu'],2,".",","),0,0,'R',0);
$pdf->SetX(140);
// $pdf->Cell(50,6,number_format(($socio['hab_f_prof']),2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Ahorros Patrono al '.convertir_fechadmy($socio['ultap_emp']),0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(30,6,number_format(($socio['hab_f_empr']+$rretiro['ret_ucla']),2,".",","),0,0,'R',0);
$pdf->SetX(110);
$pdf->Cell(30,6,number_format($rretiro['ret_ucla'],2,".",","),0,0,'R',0);
$pdf->SetX(140);
// $pdf->Cell(50,6,number_format(($socio['hab_f_prof']),2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Ahorros Voluntarios ',0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(30,6,number_format(($socio['hab_f_extr']+$rretiro['ret_volu']),2,".",","),0,0,'R',0);
$pdf->SetX(110);
$pdf->Cell(30,6,number_format($rretiro['ret_volu'],2,".",","),0,0,'R',0);
$pdf->SetX(140);
// $pdf->Cell(50,6,number_format(($socio['hab_f_prof']),2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Ahorros OPSU ',0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(30,6,number_format(($socio['hab_f_opsu']+$rretiro['ret_opsu']),2,".",","),0,0,'R',0);
$pdf->SetX(110);
$pdf->Cell(30,6,number_format($rretiro['ret_opsu'],2,".",","),0,0,'R',0);
$pdf->SetX(140);
// $pdf->Cell(50,6,number_format(($socio['hab_f_prof']),2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,6,'Saldo de Ahorros ',0,0,'R',0);
$pdf->SetX(80);
$montoahorro=$socio['hab_f_prof']+$rretiro['ret_capu']+$socio['hab_f_empr']+$rretiro['ret_ucla']+$socio['hab_f_extr']+$rretiro['ret_volu']+$socio['hab_f_opsu']+$rretiro['ret_opsu'];
$montoretiro=$rretiro['ret_capu']+$rretiro['ret_ucla']+$rretiro['ret_volu']+$rretiro['ret_opsu'];
$pdf->Cell(30,6,number_format($montoahorro,2,".",","),0,0,'R',0);
$pdf->SetX(110);
$pdf->Cell(50,6,'Monto a Retirar',0,0,'R',0);
$pdf->SetX(140);
$pdf->Cell(50,6,number_format($montoretiro,2,".",","),0,0,'R',0);
$primera=$afectan=$reintegros=$reintegroint=0;
$elasiento=$_SESSION['elasiento'];
// echo 'el elasiento '.$elasiento.'<br>';
$pdf->SetFont('Arial','',10);
if ($retiro['aprobar']==0) {	// mostrar los prestamos a cancelar
	$cedula=$socio['ced_prof'];
	$codigo=$socio['cod_prof'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql="select nropre_sdp, descr_pres, cuent_pres, cuent_int from sgcaf360,sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp=cod_pres) and (stapre_sdp='A') and (renovado=0) group by cedsoc_sdp ";
	$sql="select * from sgcaf310,sgcaf360 where (cedsoc_sdp='$micedula' and stapre_sdp='A' and (! renovado)) and (codpre_sdp=cod_pres)";
	$b = date("Y-m-d");
	$sql="select * from sgcaf701 where cedula = '$cedula' and  fecha = '$b' and tipo='-'";
	$resultado2=mysql_query($sql);
	if (mysql_num_rows($resultado2) > 0)
	while ($fila2 = mysql_fetch_assoc($resultado2))
	{
		if ($primera == 0)
			{
				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX(10);
				$pdf->Cell(0,6,'MENOS Saldos de Préstamos',0,0,'L',0);
				$primera = 1;
			}
			$saldo=$fila2['monto'];
			$afectan+=$saldo;
			$linea+=5;
			$pdf->SetY($linea);
			$pdf->SetX(10);
			$pdf->Cell(0,6,$fila2['cuenta'].' ('.$fila2['concepto'].')',0,0,'L',0);
			$pdf->SetX(80);
			$pdf->Cell(50,6,number_format($saldo,2,".",","),0,0,'R',0);
/*
		$cuenta=trim($fila2['cuent_pres']).'-'.substr($codigo,1,4);
		$saldo=buscar_saldo_f810_ret($cuenta, $elasiento); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
		if ($saldo > 0)
		{
			$afectan+=$saldo;
			$linea+=5;
			$pdf->SetY($linea);
			$pdf->SetX(10);
			$pdf->Cell(0,6,$fila2['descr_pres'].' ('.$fila2['nropre_sdp'].')',0,0,'L',0);
			$pdf->SetX(80);
			$pdf->Cell(50,6,number_format($saldo,2,".",","),0,0,'R',0);
		}
		else 
			$reintegros+=$saldo;
		if ($prestamo['int_dif']==1)
		{
			$cuenta=trim($prestamo['cuent_int']).'-'.substr($codigo,1,4);
			$saldo=buscar_saldo_f810_ret($cuenta); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
			$reintegroint=$saldo;
		}
*/
	}
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->Cell(0,6,'TOTAL PRESTAMOS',0,0,'L',0);
	$pdf->SetX(140);
	$pdf->Cell(50,6,number_format($afectan,2,".",","),0,0,'R',0);
}
//------------------------
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$reintegros=0;
$sql="select * from sgcaf701 where cedula = '$cedula' and  fecha = '$b' and tipo='+'";
$resultado2=mysql_query($sql);
if (mysql_num_rows($resultado2) > 0)
	while ($fila2 = mysql_fetch_assoc($resultado2))
	{
		if ($primera == 0)
		{
			$linea+=5;
			$pdf->SetY($linea);
			$pdf->SetX(10);
			$pdf->Cell(0,6,'MAS REINTEGROS',0,0,'L',0);
			$primera = 1;
		}
		$saldo=$fila2['monto'];
		$reintegros+=$saldo;
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX(10);
		$pdf->Cell(0,6,$fila2['cuenta'].' ('.$fila2['concepto'].')',0,0,'L',0);
		$pdf->SetX(80);
		$pdf->Cell(50,6,number_format($saldo,2,".",","),0,0,'R',0);
	}
	
/*
$pdf->Cell(0,6,'MAS REINTEGROS',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'1. Descuentos Indebidos',0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(50,6,number_format($reintegros,2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'2. Reintegros Intereses No Devengados',0,0,'L',0);
*/
$pdf->SetX(80);
$pdf->Cell(50,6,number_format($reintegroint,2,".",","),0,0,'R',0);
$pdf->SetX(140);
$pdf->Cell(50,6,number_format($reintegroint+$reintegros,2,".",","),0,0,'R',0);
// ---------------------------------
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,6,'Neto a Recibir',0,0,'R',0);
$pdf->SetX(140);
//$pdf->Cell(50,6,number_format($montoretiro-($reintegroint+$reintegros),2,".",","),0,0,'R',0);
$pdf->Cell(50,6,number_format($rretiro['netcheque'],2,".",","),0,0,'R',0);


$linea+=25;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,16,'Elaborado por',1,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(30,16,'Verificado por ',1,0,'C',0);
$pdf->SetX(110);
$pdf->Cell(30,16,'Presidente',1,0,'C',0);
$pdf->SetX(140);
$pdf->Cell(50,16,'Tesorero',1,0,'C',0);
$linea+=15;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Nro de Comprobante: '.$elasiento,0,0,'L',0);

$pdf->Output();
//$_SESSION['elasiento']='';
$elasiento='';



function convertir_fechadmy($mifecha)
{
//	$mifecha=strtotime($mifecha);
//	echo $mifecha;
	$a=explode("-",$mifecha); 
	$elano=substr($a[0],0,2);
	if ($elano="20") $b=$a[2]."/".$a[1]."/".$a[0];
	else $b=$a[2]."/".$a[1]."/"."20".$a[0];
	if ($mifecha=='--') $b='00/00/0000';
return $b;
}

function cedad($fncido)
{

     $fdhoy = explode("@", date('d@m@Y'));
     $fpncido = explode('/', $fncido);

     if($fdhoy[1] == $fpncido[1])
     {
          if($fdhoy[0] >= $fpncido[0])
          {
               $edad = $fdhoy[2] - $fpncido[2];
          }else{
               $edad = $fdhoy[2] - $fpncido[2] - 1;
          }
     }elseif($fdhoy[1] <= $fpncido[1])
     {
          $edad = $fdhoy[2] - $fpncido[2] - 1;
     }elseif($fdhoy[1] > $fpncido[1])
     {
          $edad = $fdhoy[2] - $fpncido[2];
     }

     return $edad . ' años ';
}

function num2letras($num, $fem = true, $dec = true) { 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' con '; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
} 

function buscar_saldo_f810_ret($cuenta, $elasiento)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
	$lacuentas=mysql_query($sql_f810); // or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	if (mysql_num_rows($lacuentas) > 0) {
		$lacuenta=mysql_fetch_assoc($lacuentas);
		$saldoinicial=$lacuenta['cue_saldo']; }
	else $saldoinicial = 0;
	
	$sql_f820="select com_monto1, com_monto2 from sgcaf820 where ((com_cuenta='$cuenta') and (".$_SESSION['elasiento']." != com_nrocom)) order by com_fecha";
//	echo $sql_f820;
	$lacuentas=mysql_query($sql_f820); // or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
	if (mysql_num_rows($lacuentas) > 0) {
	while($lacuenta=mysql_fetch_assoc($lacuentas)) {
		$saldoinicial+=$lacuenta['com_monto1'];
		$saldoinicial-=$lacuenta['com_monto2'];
	}
	}
return $saldoinicial;
}


/*
UPDATE `sica`.`sgcaf200` SET `hab_f_empr` = '2799',
`hab_f_prof` = '2778' WHERE CONVERT( `sgcaf200`.`ced_prof` USING utf8 ) = 'V-04739796' LIMIT 1 ;
delete from sgcaf700 where registro > 3049;
delete from sgcaf820 where com_nrocom="09060300911";
delete from sgcaf830 where enc_clave="09060300911";
*/
?>

