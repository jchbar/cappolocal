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
$cedula=$_SESSION['cedula'];
$elnumero=$_SESSION['elnumero'];
$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
$sql = "select * from sgcaf310, sgcaf360 where (nropre_sdp='$elnumero') and (cedsoc_sdp='$micedula') and (codpre_sdp=cod_pres)";
$query  = mysql_query($sql);
$prestamo = mysql_fetch_array($query);
$eltitulo=trim($prestamo['descr_pres']).' ';
if ($prestamo['aprobar']==0)
	$eltitulo.=' (Solicitud)';
else $eltitulo.='(Solicitud/Aprobación)';
$eltitulo.= ' Nro '.$elnumero;
$pdf->MultiCell(0,0,$eltitulo,0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',10);
$linea+=5;
$pdf->SetX(165);
// $pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 

$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
$socio = mysql_fetch_assoc($query);
if ($prestamo['aprobar']==0) {
	$cuento= '   Yo, '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).' mayor de edad, titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' y Socio de esta institución identificado con el Código '.$socio['cod_prof'];
	$cuento.=' me dirijo a la Junta Directiva con el fin de solicitar la cantidad de Bolívares '.strtoupper(num2letras($prestamo['monpre_sdp']-$prestamo['inicial']));
	$cuento.='  ****Bs. ('.trim(number_format($prestamo['monpre_sdp']-$prestamo['inicial'],2,".",",")).')**** en calidad de prestamo. ';
	$cuento.=' Para garantizar el pago de esta obligación doy en garantía los Ahorros que tengo en la institución' ;
	$cuento.=' y de  no ser  suficiente  avalarán   los fiadores abajo firmantes, hasta por las cantidades especificadas. ';
	$cuento.=' Dicho préstamo comenzará a ser descontado a partir del '.convertir_fechadmy3($prestamo['fecha_acta']);
}
else
{
	$cuento= '   Yo, '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).' mayor de edad, titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' y Socio de esta institución identificado con el Código '.$socio['cod_prof'];
	$cuento.=' me dirijo a la Junta Directiva con el fin de solicitar la cantidad de Bolívares '.strtoupper(num2letras($prestamo['monpre_sdp']-$prestamo['inicial']));
	$cuento.='  ****Bs. ('.trim(number_format($prestamo['monpre_sdp']-$prestamo['inicial'],2,".",",")).')**** en calidad de prestamo. ';
	$cuento.='De igual manera, en reunión de Junta Directiva según Acta N° ' . $prestamo['nro_acta'] . ' de fecha ' . convertir_fechadmy3($prestamo['fecha_acta']).' se acordó la aprobación del prestamo arriba indicado al socio ';
	$cuento.= trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).', titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' identificado con el Código '.$socio['cod_prof'];
	$cuento.=' por la cantidad de Bolívares '.strtoupper(num2letras($prestamo['monpre_sdp']-$prestamo['inicial']));
	$cuento.='  ****Bs. ('.trim(number_format($prestamo['monpre_sdp']-$prestamo['inicial'],2,".",",")).')****. ';
	$cuento.='Dicha operacion será acreditada en la Cuenta ';
	$cuento.='Nro. '.$socio['ctan_prof']. ' a partir de '.convertir_fechadmy3($prestamo['f_1cuo_sdp']);
}
$codigosocio=$socio['cod_prof'];
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

$linea+=10;
$columna=array(10,80,110,140);
$pdf->SetY($linea);
$pdf->SetX($columna[0]);
$pdf->Cell(0,6,'Prestamo concedido por la cantidad de ',0,0,'L',0);
$pdf->SetX($columna[3]);
$pdf->Cell(60,6,number_format($prestamo['monpre_sdp'],2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($columna[0]);
$pdf->Cell(80,6,'MENOS Inicial',0,0,'L',0);
$pdf->SetX($columna[3]);
$pdf->Cell(60,6,number_format($prestamo['inicial'],2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($columna[2]);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,6,'TOTAL PRESTAMO',0,0,'R',0);
$pdf->SetX($columna[3]);
$pdf->Cell(60,6,number_format(($prestamo['monpre_sdp']-$prestamo['inicial']),2,".",","),0,0,'R',0);
$pdf->SetFont('Arial','',12);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($columna[0]);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'MENOS Deducciones',0,0,'L',0);
$pdf->SetFont('Arial','',12);

$t_deduccion=0;
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($columna[0]);
$pdf->Cell(0,6,'Intereses cobrados por anticipado al '.number_format($prestamo['interes_sd'],2,".",",").'%',0,0,'L',0);
$pdf->SetX($columna[2]);
$pdf->Cell(60,6,number_format($prestamo['intereses'],2,".",","),0,0,'R',0);
$t_deduccion+=$prestamo['intereses'];

$sql_deducciones="select * from sgcaf312 where cedula='$micedula' and numero = '$elnumero' and tipo = '-' order by cuento ";
$a_deduccion=mysql_query($sql_deducciones);
while($r_deduccion=mysql_fetch_assoc($a_deduccion)) {
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX($columna[0]);
	$pdf->Cell(0,6,$r_deduccion['cuento'],0,0,'L',0);
	$pdf->SetX($columna[2]);
	$pdf->Cell(60,6,number_format($r_deduccion['monto'],2,".",","),0,0,'R',0);
	$t_deduccion+=$r_deduccion['monto'];
}

$linea+=5;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',12);
$pdf->SetX($columna[2]);
$pdf->Cell(60,6,'Total Deducciones',0,0,'R',0);
$pdf->SetX($columna[3]);
$pdf->Cell(60,6,number_format($t_deduccion,2,".",","),0,0,'R',0);
$pdf->SetFont('Arial','',12);


$t_reintegro=0;
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($columna[0]);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'MAS Reintegros',0,0,'L',0);
$pdf->SetFont('Arial','',12);

$sql_deducciones="select * from sgcaf312 where cedula='$micedula' and numero = '$elnumero' and tipo = '+' order by cuento ";
$a_deduccion=mysql_query($sql_deducciones);
while($r_deduccion=mysql_fetch_assoc($a_deduccion)) {
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX($columna[0]);
	$pdf->Cell(0,6,$r_deduccion['cuento'],0,0,'L',0);
	$pdf->SetX($columna[2]);
	$pdf->Cell(60,6,number_format($r_deduccion['monto'],2,".",","),0,0,'R',0);
	$t_reintegro+=$r_deduccion['monto'];
}

$linea+=5;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',12);
$pdf->SetX($columna[2]);
$pdf->Cell(60,6,'Total Reintegros',0,0,'R',0);
$pdf->SetX($columna[3]);
$pdf->Cell(60,6,number_format($t_reintegro,2,".",","),0,0,'R',0);
$pdf->SetFont('Arial','',12);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($columna[2]);
$pdf->Cell(60,6,'Neto a Recibir',0,0,'R',0);
$pdf->SetX($columna[3]);
$neto=($prestamo['monpre_sdp']-$prestamo['inicial'])+$t_reintegro-$t_deduccion;
$pdf->Cell(60,6,number_format($neto,2,".",","),0,0,'R',0);
$pdf->SetFont('Arial','',12);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($columna[0]);
$pdf->Cell(0,6,'Condiciones del Crédito',0,0,'C',0);
$pdf->SetFont('Arial','',10);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($columna[0]);
$cuento=trim($prestamo['descr_pres']). ' otorgado por la cantidad de Bs. '.number_format(($prestamo['monpre_sdp']-$prestamo['inicial']),2,".",",");
$cuento.=' entre la forma en que se propone cancelar el préstamo ' . trim($prestamo['nrocuotas']) . ' cuotas de Bs. ';
$cuento.=strtoupper(num2letras($prestamo['cuota'])); 
$cuento.=' Bs. ('.number_format($prestamo['cuota'],2,".",",").') ';
$cuento.=' *** LOS PRESTAMOS ESPECIALES NO TIENEN DERECHO A REINTEGRO *** En caso de indomiciliacion procedera suspension para prestamos por noventa (90) dias calendario UCLA y reactivado treinta (30) posterior a su correcta domiciliacion ';
$pdf->MultiCell(0,5,$cuento,0,'L'); 

// fiadores
$sql_fiador="select * from sgcaf320, sgcaf200 where (codsoc_fia='$codigosocio') and (nropre_fia = '$elnumero') and (codfia_fia=cod_prof)";
$a_fiador= mysql_query($sql_fiador);
// echo $sql_fiador;
if (mysql_num_rows($a_fiador) > 0) {
	$linea+=15;
	$pdf->SetY($linea);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX($columna[0]);
	$pdf->Cell(0,6,'Espacio para ser llenado por los fiadores',0,0,'C',0);
	$pdf->SetFont('Arial','',12);

	$header=array('Apellidos y Nombres','Nro. C.I.','Monto Fianza','Firma','Revisado por');
	//Cabecera
    $w=array(60,40, 30, 20, 40);
	$c=array(10,70,110,140,160);
	$linea+=5;
	$pdf->SetY($linea);
    for($i=0;$i<count($header);$i++) {
		$pdf->SetX($c[$i]);
        $pdf->Cell($w[$i],6,$header[$i],1,0,'C',0);
	}

	while($r_fiador=mysql_fetch_assoc($a_fiador)) {
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX($c[0]);
		$pdf->Cell($w[0],6,trim($r_fiador['ape_prof']).' '.trim($r_fiador['nombr_prof']));
		$pdf->SetX($c[1]);
		$pdf->Cell($w[1],6,$r_fiador['ced_prof'],0,0,'L',0);
		$pdf->SetX($c[2]);
		$pdf->Cell($w[2],6,number_format($r_fiador['monto_fia'],2,".",","),0,0,'R',0);
	}	
}


//$linea+=25;
$linea+=20;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Elaborado por',1,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(30,6,'Verificado por ',1,0,'C',0);
$pdf->SetX(110);
$pdf->Cell(30,6,'Presidente',1,0,'C',0);
$pdf->SetX(140);
$pdf->Cell(50,6,'Tesorero',1,0,'C',0);
$linea+=15;
$pdf->SetY($linea);
$pdf->SetX(10);
$elasiento=$_SESSION['elasiento'];
$pdf->Cell(70,6,'Nro de Comprobante: '.$elasiento,0,0,'L',0);

// revisar si tienen planilla de descuento especial con nominas
// ------------
if ($prestamo['pla_autor']==1) {
//  $pdf->AddPage();
  $linea+=15;
	$pdf->SetFont('Arial','',10);
  $pdf->SetY($linea);
  $pdf->SetX(20);

	$cuento= '   Yo, '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']).' titular de la Cédula de Identidad: ';
	$cuento.=$socio['ced_prof'].' y Socio de esta institución identificado con el Código '.$socio['cod_prof'];
  $cuento.=' de la Caja de Ahorro y Préstamo del Personal ';
  $cuento.='Obrero de la Universidad Centroccidental "Lisandro Alvarado" (CAPPO-UCLA)';
  $cuento.=', autorizo a la Administración de la Caja de Ahorro para que me sea descontado POR ';
  $cuento.='UNA SOLA VEZ de la(s) nómina(s) correspondientes a PAGOS ESPECIALES, que cancela la ';
  $cuento.='UCLA; por la cantidad de BOLIVARES ';
  $cuento.=strtoupper(num2letras($prestamo['cuota'])); 
  $cuento.=' por concepto de '.trim($prestamo['descr_pres']);

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
  
/*
  // repito
  $linea+=10;
  $pdf->SetY($linea);
  $pdf->SetX(0);

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
*/  
}

$pdf->Output();
// $_SESSION['elasiento']='';
$elasiento='';



function convertir_fechadmy3($mifecha)
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

/*
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

*/
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

?>

