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
	
include("head.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
	*/ 

// $link = @mysql_connect("localhost","root", "",'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexión con el servidor, inténtalo más tarde.</div>");
// mysql_select_db($_POST['sica'], $link);

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
// include ("conex.php"); 

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"E S T A D O   D E   C U E N T A",0,C,0);
$pdf->SetY($linea);
echo ''; 
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 

$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
$socio = mysql_fetch_array($query);
$tiene = $socio['polizaactiva'];
if ($tiene == 1)
	$poliza='Poliza de Vida: Activa';
else 
	$poliza='Poliza de Vida: NO ACTIVA';
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',($tiene==1?12:14));
$pdf->MultiCell(0,0,$poliza,0,C,0);

if ($socio['AyudaSolidaria'] != 'Si')
{
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX(0);
	// $pdf->SetFont('Arial','B',($tiene==1?14:20));
	$pdf->MultiCell(0,0,'Se encuentra retirado de APORTE AYUDA SOLIDARIA ('.$socio['FechaRetiroAyuda'].')',0,C,0);
}
if ($socio['AhorroVoluntario'] != 'Si')
{
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX(0);
	// $pdf->SetFont('Arial','B',($tiene==1?14:20));
	$pdf->MultiCell(0,0,'Se encuentra retirado de AHORRO VOLUNTARIO',0,C,0);
}

$linea+=3;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(190,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=6;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5, $socio['ape_prof'].' '.$socio['nombr_prof'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(18,5,'Cédula:',0,0,'L',0);
$pdf->SetX(99);
$pdf->Cell(42,5,$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Código:',0,0,'L',0);
$pdf->SetX(159);
$pdf->Cell(41,5,$socio['cod_prof'],0,0,'L',0);

$hoy = date("Y-m-d H:i:s");
$codigo=$socio['cod_prof'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$ibuscar="insert into hedocta (codigo, cedula, fecha, ip) values ('$codigo',' $cedula', '$hoy', '$ip')";
$rib=mysql_query($ibuscar);


$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Ing.Caja '.convertir_fechadmy1($socio['f_ing_capu']),0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5,'Ing.UCLA '.convertir_fechadmy1($socio['f_ing_ucla']),0,0,'L',0);
$pdf->SetX(81);
$elcescuela=$socio['escuela'];
$sql="select codigo, nombre from escuelas where codigo = '$elcescuela'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(18,5,'Dependencia: ',0,0,'L',0);
$eldecanato=$fila2['nombre'];
$pdf->SetX(99);
$pdf->Cell(42,5,$fila2['nombre'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Departamento ',0,0,'L',0);
$pdf->SetX(159);
$elcdpto=$socio['dept_prof'];
$sql="select escdpto, escuela from sgcafeyd where escdpto = '$elcdpto'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(41,5,$fila2['escuela'],0,0,'L',0);
$cedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
$sql="select * from unido where cedula= '$cedula' and ano='2004' order by ano";
$linea+=4;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Cargo: '.$socio['cargo'],0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(28,5,'Estatus: '.$socio['statu_prof'],0,0,'L',0);
$linea+=6;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(150,6,'DESCRIPCIÓN',0,0,'C',1);
$pdf->SetFont('Arial','B',7);
$pdf->SetX(160);
$pdf->Cell(20,6,'Ahorros Bs. F.',0,0,'C',1);
$pdf->SetX(180);
$pdf->Cell(20,6,'Total Bs. F.',0,0,'C',1);

$linea+=6;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$sql_aporte="SELECT * FROM aportep WHERE tipo = 'R' ORDER BY fecha DESC LIMIT 1 ";
$resul_aporte=mysql_query($sql_aporte);
$faporte=mysql_fetch_assoc($resul_aporte);
// $pdf->Cell(150,6,'Ahorro Socio al:'.convertir_fechadmy1($faporte['fecha']),0,0,'L',0);
$pdf->Cell(150,6,'Ahorro Socio al:'.convertir_fechadmy1($socio['ultap_prof']),0,0,'L',0);
$pdf->SetX(160);
$pdf->Cell(20,6,number_format($socio['hab_f_prof'],2,".",","),0,0,'R',0);
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$sql_aporte="SELECT * FROM aportep WHERE tipo = 'A' ORDER BY fecha DESC LIMIT 1 ";
$resul_aporte=mysql_query($sql_aporte);
$faporte=mysql_fetch_assoc($resul_aporte);
$pdf->Cell(150,6,'Ahorro UCLA al:'.convertir_fechadmy1($faporte['fecha']),0,0,'L',0);
$pdf->SetX(160);
$pdf->Cell(20,6,number_format($socio['hab_f_empr'],2,".",","),0,0,'R',0);
/*
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(150,6,'veBono 2009',0,0,'L',0);
$pdf->SetX(160);
$pdf->Cell(20,6,number_format($socio['hab_opsu'],2,".",","),0,0,'R',0);
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(150,6,'Ahorro Voluntarios',0,0,'L',0);
$pdf->SetX(160);
$pdf->Cell(20,6,number_format($socio['hab_f_extr'],2,".",","),0,0,'R',0);
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(150,6,'Ahorro Capitalizables',0,0,'L',0);
$pdf->SetX(160);
$pdf->Cell(20,6,number_format($socio['hab_f_capi'],2,".",","),0,0,'R',0);
$linea+=4;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
*/
$pdf->Cell(170,6,'SALDOS AHORROS',0,0,'R',0);
$pdf->SetX(180);
$totalahorros=$socio['hab_f_prof']+$socio['hab_f_extr']+$socio['hab_f_capi']+$socio['hab_f_empr']+$socio['hab_opsu'];
$pdf->Cell(20,6,number_format(($totalahorros),2,".",","),0,0,'R',1);

$sql="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$cedula' and codpre_sdp=cod_pres and stapre_sdp='A' and ! renovado) order by f_1cuo_sdp";
$result=mysql_query($sql);
$registros12=mysql_num_rows($result);
if ($registros12 > 0) {
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(190,6,'SALDO DEL PRESTAMO AL',0,0,'C',1);

$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(13,6,'#Prest.',0,0,'C',1);
$pdf->SetX(23);
$pdf->Cell(57,6,'Tipo de Préstamo',0,0,'C',1);
$pdf->SetX(80);
$pdf->Cell(20,6,'Monto',0,0,'C',1);
$pdf->SetX(100);
$pdf->Cell(20,6,'#NC',0,0,'C',1);
$pdf->SetX(120);
$pdf->Cell(20,6,'CC',0,0,'C',1);
$pdf->SetX(140);
$pdf->Cell(20,6,'Cuota',0,0,'C',1);
$pdf->SetX(160);
$pdf->Cell(20,6,'1er Dcto.',0,0,'C',1);
$pdf->SetX(180);
$pdf->Cell(20,6,'Saldo',0,0,'C',1);
$linea+=2;
//echo $sql.'<br>';
$result=mysql_query($sql);
$fianzas = $afectan = $noafectan = $semanal = 0;
while($row=mysql_fetch_assoc($result)) {
$linea+=4;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(13,6,$row['nropre_sdp'],0,0,'C',0);
$pdf->SetX(23);
$pdf->Cell(57,6,trim($row['descr_pres']),0,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(20,6,number_format($row['monpre_sdp'],2,".",",").' '.($row['enUSD']=='1'?'(USD)':''),0,0,'C',0);
$pdf->SetX(100);
$pdf->Cell(20,6,number_format($row['nrocuotas'],0,",","."),0,0,'C',0);
$pdf->SetX(120);
$pdf->Cell(20,6,number_format($row['ultcan_sdp'],0,",","."),0,0,'C',0);
$pdf->SetX(140);
$pdf->Cell(20,6,number_format($row['cuota_ucla'],2,".",",").' '.($row['enUSD']=='1'?'(USD)':''),0,0,'C',0);
$pdf->SetX(160);
$pdf->Cell(20,6,convertir_fechadmy1($row['f_1cuo_sdp']),0,0,'C',0);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format(($row['monpre_sdp']-$row['monpag_sdp']),2,".",",").' '.($row['enUSD']=='1'?'(USD)':''),0,0,'R',0);
if ($row['retab_pres']==1)
		$afectan +=($row['monpre_sdp']-$row['monpag_sdp']);
	else $noafectan += ($row['monpre_sdp']-$row['monpag_sdp']);
	if ($row['dcto_sem']==1)
		$semanal += $row['cuota_ucla'];
		if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
						$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"E S T A D O   D E   C U E N T A",0,C,0);
$pdf->SetY($linea);
echo ''; 
$pdf->SetFont('Arial','',10);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
$socio = mysql_fetch_array($query);
$linea+=3;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=6;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5, $socio['ape_prof'].' '.$socio['nombr_prof'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(18,5,'Cédula:',0,0,'L',0);
$pdf->SetX(99);
$pdf->Cell(42,5,$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Código:',0,0,'L',0);
$pdf->SetX(159);
$pdf->Cell(41,5,$socio['cod_prof'],0,0,'L',0);
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Fecha de Ingreso:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5,convertir_fechadmy1($socio['f_ing_capu']),0,0,'L',0);
$pdf->SetX(81);
$elcescuela=$socio['escuela'];
$sql="select codigo, nombre from escuelas where codigo = '$elcescuela'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(18,5,'Dependencia: ',0,0,'L',0);
$eldecanato=$fila2['nombre'];
$pdf->SetX(99);
$pdf->Cell(42,5,$fila2['nombre'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Departamento ',0,0,'L',0);
$pdf->SetX(159);
$elcdpto=$socio['dept_prof'];
$sql="select escdpto, escuela from sgcafeyd where escdpto = '$elcdpto'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(41,5,$fila2['escuela'],0,0,'L',0);
$cedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
$sql="select * from unido where cedula= '$cedula' and ano='2004' order by ano";
}
   }
}
$fiado=0;
$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia=cod_prof) and (codfia_fia='".$socio['cod_prof']."') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia='".$socio['cod_prof']."') and (codfia_fia=cod_prof) and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
//$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia=cod_prof) and (codfia_fia='".$socio['cod_prof']."') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
// $sql="select *, (monto_fia-monlib_fia) as saldo_fia from sgcaf320 where (codsoc_fia='".$fila['cod_prof']."') and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
//echo $sql.'<br>';
$afianzadores=mysql_query($sql);
$registros1=mysql_num_rows($afianzadores);
if ($registros1 > 0) {

$linea+=6;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(190,6,'FIANZAS RECIBIDAS',0,0,'C',1);

$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(13,6,'#Prest.',0,0,'C',1);
$pdf->SetX(23);
$pdf->Cell(57,6,'Fiado',0,0,'C',1);
$pdf->SetX(80);
$pdf->Cell(40,6,'Monto Otorgado',0,0,'C',1);
$pdf->SetX(120);
$pdf->Cell(40,6,'Monto Liberado',0,0,'C',1);
$pdf->SetX(160);
$pdf->Cell(40,6,'Monto por Liberar',0,0,'C',1);
$linea+=2;
     while($afianzado=mysql_fetch_assoc($afianzadores)) {
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(13,6,$afianzado['nropre_fia'],0,0,'C',0);
$pdf->SetX(23);
$pdf->Cell(57,6,$afianzado['ape_prof'].' '.$afianzado['nombr_prof'],0,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(40,6,number_format($afianzado['monto_fia'],2,".",","),0,0,'C',0);
$pdf->SetX(120);
$pdf->Cell(40,6,number_format($afianzado['monlib_fia'],2,".",","),0,0,'C',0);
$pdf->SetX(160);
$pdf->Cell(40,6,number_format($afianzado['saldo_fia'],2,".",","),0,0,'C',0);
$fiado+= ($afianzado['saldo_fia']);
if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
						$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"E S T A D O   D E   C U E N T A",0,C,0);
$pdf->SetY($linea);
echo ''; 
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
$socio = mysql_fetch_array($query);
$linea+=3;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=6;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5, $socio['ape_prof'].' '.$socio['nombr_prof'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(18,5,'Cédula:',0,0,'L',0);
$pdf->SetX(99);
$pdf->Cell(42,5,$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Código:',0,0,'L',0);
$pdf->SetX(159);
$pdf->Cell(41,5,$socio['cod_prof'],0,0,'L',0);
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Fecha de Ingreso:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5,convertir_fechadmy1($socio['f_ing_capu']),0,0,'L',0);
$pdf->SetX(81);
$elcescuela=$socio['escuela'];
$sql="select codigo, nombre from escuelas where codigo = '$elcescuela'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(18,5,'Dependencia: ',0,0,'L',0);
$eldecanato=$fila2['nombre'];
$pdf->SetX(99);
$pdf->Cell(42,5,$fila2['nombre'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Departamento ',0,0,'L',0);
$pdf->SetX(159);
$elcdpto=$socio['dept_prof'];
$sql="select escdpto, escuela from sgcafeyd where escdpto = '$elcdpto'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(41,5,$fila2['escuela'],0,0,'L',0);
$cedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
$sql="select * from unido where cedula= '$cedula' and ano='2004' order by ano";
}
	   }
}

$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia=cod_prof) and (codfia_fia='".$socio['cod_prof']."') and ((monto_fia-monlib_fia) > 0) and (tipmov_fia='F') order by codsoc_fia";
//$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia='".$socio['cod_prof']."') and (codfia_fia=cod_prof) and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
// $sql="select *, (monto_fia-monlib_fia) as saldo_fia from sgcaf320 where (codsoc_fia='".$fila['cod_prof']."') and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
//echo $sql.'<br>';

$fiadores=mysql_query($sql);
$registros=mysql_num_rows($fiadores);
if ($registros > 0) {
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(190,6,'FIANZAS OTORGADAS',0,0,'C',1);

$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(13,6,'#Prest.',0,0,'C',0);
$pdf->SetX(23);
$pdf->Cell(57,6,'Fiador',0,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(40,6,'Monto Otorgado',0,0,'C',0);
$pdf->SetX(120);
$pdf->Cell(40,6,'Monto Liberado',0,0,'C',0);
$pdf->SetX(160);
$pdf->Cell(40,6,'Saldo Actual',0,0,'C',0);
$linea+=2;
   while($fiador=mysql_fetch_assoc($fiadores)) {
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(13,6,$fiador['nropre_fia'],0,0,'C',0);
$pdf->SetX(23);
$pdf->Cell(57,6,$fiador['ape_prof'].' '.$fiador['nombr_prof'],0,0,'C',0);
$pdf->SetX(80);
$pdf->Cell(40,6,number_format($fiador['monto_fia'],2,".",","),0,0,'C',0);
$pdf->SetX(120);
$pdf->Cell(40,6,number_format($fiador['monlib_fia'],2,".",","),0,0,'C',0);
$pdf->SetX(160);
$pdf->Cell(40,6,number_format($fiador['saldo_fia'],2,".",","),0,0,'C',0);
$fianzas+=($fiador['saldo_fia']);
if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
						$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"E S T A D O   D E   C U E N T A",0,C,0);
$pdf->SetY($linea);
echo ''; 
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
$socio = mysql_fetch_array($query);
$linea+=3;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=6;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5, $socio['ape_prof'].' '.$socio['nombr_prof'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(18,5,'Cédula:',0,0,'L',0);
$pdf->SetX(99);
$pdf->Cell(42,5,$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Código:',0,0,'L',0);
$pdf->SetX(159);
$pdf->Cell(41,5,$socio['cod_prof'],0,0,'L',0);
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(28,5,'Fecha de Ingreso:',0,0,'L',0);
$pdf->SetX(38);
$pdf->Cell(43,5,convertir_fechadmy1($socio['f_ing_capu']),0,0,'L',0);
$pdf->SetX(81);
$elcescuela=$socio['escuela'];
$sql="select codigo, nombre from escuelas where codigo = '$elcescuela'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(18,5,'Dependencia: ',0,0,'L',0);
$eldecanato=$fila2['nombre'];
$pdf->SetX(99);
$pdf->Cell(42,5,$fila2['nombre'],0,0,'L',0);
$pdf->SetX(141);
$pdf->Cell(18,5,'Departamento ',0,0,'L',0);
$pdf->SetX(159);
$elcdpto=$socio['dept_prof'];
$sql="select escdpto, escuela from sgcafeyd where escdpto = '$elcdpto'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(41,5,$fila2['escuela'],0,0,'L',0);
$cedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
$sql="select * from unido where cedula= '$cedula' and ano='2004' order by ano";
}
	}
}
$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Total Saldos Fianzas Recibidas:',0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(25,6,number_format($fiado,2,".",","),0,0,'R',0);
$pdf->SetX(105);
$pdf->Cell(75,6,'Total Saldos Fianzas Otorgadas:',0,0,'R',0);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($fianzas,2,".",","),0,0,'R',1);

$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Total Saldos que NO Afectan Disponibilidad',0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(25,6,number_format($noafectan,2,".",","),0,0,'R',0);
$pdf->SetX(105);
$pdf->Cell(75,6,'Total Saldos que Afectan Disponibilidad',0,0,'R',0);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($afectan,2,".",","),0,0,'R',1);

$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Total Cuota a Banco Quincenal',0,0,'L',0);
$pdf->SetX(80);
$pdf->Cell(25,6,number_format($semanal,2,".",","),0,0,'R',0);
$pdf->SetX(105);
$sql="select por_dispon from sgcaf100 limit 1";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$reserva=$totalahorros*($row['por_dispon']/100);
$pdf->Cell(75,6, 'Monto por Reserva Legal ('.number_format($row['por_dispon'],2,'.','.').'%)',0,0,'R',0);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($reserva,2,".",","),0,0,'R',1);
// nuevo 2012-10-21
$linea+=5;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$sql="select * from sgcaf000 where tipo ='Reserva_P_Esp02'";
$a_sql=mysql_query($sql);
$r_sql=mysql_fetch_assoc($a_sql);
$porc_esp=($r_sql['nombre']/100);
$reserva2=0;
$reserva2=$noafectan * $porc_esp;
$sql="select * from sgcaf000 where tipo ='Reserva_P_Esp01'";
$a_sql=mysql_query($sql);
$r_sql=mysql_fetch_assoc($a_sql);
$nombre_reserva=$r_sql['nombre'];

$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(50,6,$nombre_reserva,0,0,'R',0);
$pdf->SetX(80);
$pdf->Cell(20,6,number_format($reserva2,2,".",","),0,0,'R',0);
$disponibilidad=($totalahorros-$reserva-$reserva2)-($afectan+$fianzas);
// fin nuevo 2012-10-21
//$disponibilidad=($totalahorros-$reserva)-($afectan+$fianzas);
if ($disponibilidad >= 0) {
//$linea+=5;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(170,6,'Disponibilidad Neta ',0,0,'R',0);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($disponibilidad,2,".",","),0,0,'R',0);
}
else {
// $linea+=5;
$pdf->SetFont('Arial','BU',9);
$pdf->SetY($linea);
$pdf->SetX(110);
$pdf->Cell(70,6,'Disponibilidad Neta',0,0,'R',1);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($disponibilidad,2,".",","),0,0,'R',1);
}
	$sql2="select sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 where cod_prof = '".$socio['cod_prof']."' group by cod_prof";
//	echo $sql2;
	$result2=mysql_query($sql2);
	$row2=mysql_fetch_assoc($result2);
//	echo '<tr><td align="right" class="rojo b" colspan="7">Monto Adeudado por la UCLA </td><td class="rojo b" align="right">'.number_format($row2[socio]+$row2[ucla],2,'.',',').'</td>';
$linea+=5;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(170,6,'Monto Adeudado por la UCLA',0,0,'R',1);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($row2[socio]+$row2[ucla],2,".",","),0,0,'R',0);

	$sql2="select * from sgcaf700 where codsoc = '".$socio['cod_prof']."' order by fechareti desc limit 1";
//	echo $sql2;
	$result2=mysql_query($sql2);
	$row2=mysql_fetch_assoc($result2);
//	echo '<tr><td align="right" class="rojo b" colspan="7">Ultimo retiro realizado el '.convertir_fechadmy($row2['fechareti']).' por concepto de '.$row2['motivo'].' </td><td class="rojo b" align="right">'.number_format($row2[montoreti],2,'.',',').'</td>';
$linea+=5;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(170,6,'Ultimo retiro realizado el '.convertir_fechadmy1($row2['fechareti']).' por concepto de '.$row2['motivo'],0,0,'R',1);
$pdf->SetX(180);
$pdf->Cell(20,6,number_format($row2[montoreti],2,".",","),0,0,'R',1);
		
//

	/* revisar si esta suspendido */
	$micedula=$cedula; // substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sqls="select *, date_format(suspendido,'%d/%m/%y') as fs, date_format(ingresado,'%d/%m/%y') as ingresadof  from suspende where ((cedula = '$micedula') and  (activo = 1) and (now() < suspendido))";
//	echo $sqls;
	$resuls=mysql_query($sqls);
	$vacio=(mysql_num_rows($resuls) > 0?true:false);
	$loquedebe='';
	while ($fila2 = mysql_fetch_assoc($resuls)) {
//		echo '<h2>No se pudo descontar prestamo '.$fila2['prestamo']. ' enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['suspendido']. ' reportado por '.$fila2['reporto'].'</h2>';
		$loquedebe.='No se pudo descontar prestamo '.$fila2['prestamo']. ' enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['fs']. ' reportado por '.$fila2['reporto'].' en fecha '.$fila2['ingresadof'].' / ';
	}
	if ($vacio == true) // esta suspendido
//		die('<h1>No puede solicitar prestamos</h1>');

	/* fin revisar si esta suspendido */
$linea+=5;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->MultiCell(0,5,$loquedebe,0,C,0);

header('Content-Type: application/pdf');
$pdf->Output();


function convertir_fechadmy1($mifecha)
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
function cedad1($fncido)
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
?>