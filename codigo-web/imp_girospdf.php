<?php
require('fpdf/pdf_js.php');

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
	//Open the print dialog or start printing immediately on the standard printer
	$param=($dialog ? 'true' : 'false');
	$script="print($param);";
	$this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
	//Print on a shared printer (requires at least Acrobat 6)
	$script = "var pp = getPrintParams();";
	if($dialog)
		$script .= "pp.interactive = pp.constants.interactionLevel.full;";
	else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
	$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
	$script .= "print(pp);";
	$this->IncludeJS($script);
}
}

session_start();

// include("fpdf/a_cookies.php");
extract($_GET);
extract($_POST);
extract($_SESSION);

    include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
    include("head.php");
	//header("location: noempresa.php");
	exit;
}
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");

$pdf=new PDF_AutoPrint();
// $pdf->AddPage();
$pdf->Open();
$linea=1;
$pdf->SetY($linea);
// giros
$creacion='SELECT now() as fecha';
$query  = mysql_query($creacion);
$hoy=mysql_fetch_assoc($query);
$hoy=substr($hoy['fecha'],0,10);
$sql = "select * from sgcaf310, sgcaf200 where (cedsoc_sdp='$micedula') and ((codpre_sdp='034') or (codpre_sdp='069')) and (codsoc_sdp=cod_prof) and (stapre_sdp='A') and (renovado = 0) and (f_soli_sdp='$hoy') order by nropre_sdp";
$primeralinea=8-2;
$segundalinea=17-2;
$terceralinea=45-2;
// echo $sql;
$query  = mysql_query($sql);
while($r_giros=mysql_fetch_assoc($query)) {
	$pdf->AddPage();
	$linea=$primeralinea;
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($linea);
	$pdf->SetX(60);
	$pdf->Cell(0,6,'Barquisimeto',0,0,'L',0);
	$pdf->SetX(90);
	$pdf->Cell(0,6,substr($r_giros['f_soli_sdp'],8,2).'/'.substr($r_giros['f_soli_sdp'],5,2).'/'.substr($r_giros['f_soli_sdp'],0,4),0,0,'L',0);
	$pdf->SetX(98);
/*
	$pdf->Cell(0,6,substr($r_giros['f_soli_sdp'],5,2),0,0,'L',0);
	$pdf->SetX(110);
	$pdf->Cell(0,6,substr($r_giros['f_soli_sdp'],0,4),0,0,'L',0);
*/
	$pdf->SetFont('Arial','B',14);
	$pdf->SetX(125);
	$pdf->Cell(0,6,trim('****'.number_format($r_giros['monpre_sdp'],2,".",",")).'****',0,0,'L',0);
	$pdf->SetX(170);
	$pdf->Cell(0,6,substr($r_giros['nropre_sdp'],6,2),0,0,'L',0);
	$pdf->SetFont('Arial','B',12);

	$linea=$segundalinea;
	$pdf->SetY($linea);
	$pdf->SetX(110);
	$pdf->Cell(0,6,substr($r_giros['f_1cuo_sdp'],8,2).'/'.substr($r_giros['f_1cuo_sdp'],5,2).'/'.substr($r_giros['f_1cuo_sdp'],0,4),0,0,'L',0);
/*
	$pdf->SetX(118);
	$pdf->Cell(0,6,substr($r_giros['f_1cuo_sdp'],5,2),0,0,'L',0);
	$pdf->SetX(125);
	$pdf->Cell(0,6,substr($r_giros['f_1cuo_sdp'],0,4),0,0,'L',0);
*/

	$linea=$terceralinea;
	$pdf->SetY($linea);
	$pdf->SetX(50);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(0,6,strtoupper(num2letras($r_giros['monpre_sdp'])) . ' EXACTOS ',0,0,'L',0);
	$pdf->SetFont('Arial','B',12);
	
	$linea+=15;
	$pdf->SetY($linea);
	$pdf->SetX(150);
	$pdf->Cell(0,6,strtoupper('ENTENDIDO'),0,0,'L',0);

	$linea+=10;
	$pdf->SetY($linea);
	$pdf->SetX(30);
	$pdf->Cell(0,6,$r_giros['ape_prof']. ' '.$r_giros['nombr_prof'],0,0,'L',0);
	
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX(30);
	$pdf->Cell(0,6,$r_giros['cod_prof'],0,0,'L',0);
	$pdf->SetX(80);
	$pdf->Cell(0,6,$r_giros['ced_prof'],0,0,'L',0);
	
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX(30);
	$pdf->Cell(0,6,$r_giros['dirn1_prof'],0,0,'L',0);

	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX(30);
	$pdf->Cell(0,6,trim($r_giros['dirn2_prof']).'/'.$r_giros['teln_prof']. ' '.$r_giros['celn_prof'].' '.$r_giros['cel2n_prof'],0,0,'L',0);
//	$pdf->SetX(70);
//	$pdf->Cell(0,6,$r_giros['teln_prof']. ' '.$r_giros['celn_prof'].' '.$r_giros['cel2n_prof'],0,0,'L',0);
	
}

// $pdf->AutoPrint(true);
$pdf->Output();

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
      $fin = ' coma'; 
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
         $subcent = 'os'; // as
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