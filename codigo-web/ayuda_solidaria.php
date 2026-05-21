<?php

session_start();

extract($_GET);
extract($_POST);
extract($_SESSION);
// $fechaaporte='2023-02-16';

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}
include_once('funciones.php');

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 
// echo $fechanomina;
$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

$linea=0;
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(0,0,"Listado de Ayuda Solidaria al ".convertir_fechadmy($fechaaporte),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$linea+=5;
$comisionbancaria = 5;

$header=array('Cédula','Apellidos y Nombres','Monto','','Cédula','Apellidos y Nombres','Monto',''); 
$w=array(15, 40, 15,10, 15, 40,15,10);
$p=array(10, 30, 80, 95, 105, 125, 175,190);

// $header=array('Cédula','Apellidos y Nombres','','Cédula','Apellidos y Nombres','','Cédula','Apellidos y Nombres'); 
// $w=array(15, 40, 10, 15, 40, 10, 15, 40);
// $p=array();
// $anterior = 10;
// $contador=0;
// foreach ($w as $key => $value) {
// 	array_push($p, $anterior);
// 	if ($contador > 0)
// 		$anterior+=$value;
// 	$contador++;
// }

$pdf->SetY($linea);
$pdf->SetFont('Arial','B',8);
$pdf->SetX(30);
	
//Cabecera
// $pc=$w[0];
// $p=array();
// array_push($p,$pc);
for($i=0;$i<count($header);$i++)
{
	$pdf->SetX($p[$i]);
    $pdf->Cell($w[$i],7,$header[$i],0,0,'C',1);
	// $pc+=$w[$i+1];
	// array_push($p,$pc);
}


// $sql="select * from t_his200, sgcaf200 where (fecha ='$fechaaporte') and (t_his200.cedula = sgcaf200.ced_prof) and pertenece='AS' order by cedula";
$sql = "select * from sgcaf100 limit 1";
$resultado=mysql_query($sql);
$rv = mysql_fetch_assoc($resultado);
$monto = $rv['AyudaSolidaria'];

$sql = "select * from sgcaf200 where AyudaSolidaria = 'Si' and (tipo_socio = 'P') and 
	((upper(statu_prof)='ACTIVO') or (upper(statu_prof)='JUBILA'))";
$result=mysql_query($sql) or die (mysql_error());
$emonto = $emonto2 = 0;
$maxlineas=235;
$pdf->SetFont('Arial','',8);
$cont='0'; 
$columna=0;
while ($row = mysql_fetch_array($result))
{
 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
 //imprimo nombre, apellidos y localidad
	$linea+=4;
	$pdf->SetY($linea);
	$pdf->SetX($p[0+$columna]);
    // $asterisco = (((trim($row['pertenece']) == 'AO') or (trim($row['pertenece']) == 'JO'))?'*':'');
	// $pdf->Cell($w[0+$columna],5,$row['cod_prof'],0,0,'LRTB',0); 
	// $pdf->SetX($p[1+$columna]);
	$pdf->Cell($w[0+$columna],5,$row['ced_prof'],0,0,'LRTB',0); 
	$cont++; 
	$acotado = substr(trim($row["ape_prof"]).' '.trim($row["nombr_prof"]),0,30);
	$pdf->SetX($p[1+$columna]);
    $pdf->Cell($w[1+$columna],5,$acotado,0,0,'LRTB',0);  
    // $asterisco = (((trim($row['pertenece']) == 'AO') or (trim($row['pertenece']) == 'JO'))?'*':'');
	$pdf->SetX($p[2+$columna]);
	$pdf->Cell($w[2+$columna],5,number_format($monto,2,".",","),0,0,'R',0);
	$pdf->SetX($p[3+$columna]);
	// $pdf->Cell($w[3+$columna],5,$row['pertenece'].$asterisco,0,0,'L',0);
	$emonto+=$monto;
	if ($linea >= $maxlineas)
	{
		$columna+=4;
		if ($columna < 6)
		{
			$linea+=4;
			$pdf->SetY($linea);
			$pdf->SetX($p[1]);
		    $pdf->Cell($w[1],5,'Van....',0,0,'C',0);  
			$pdf->SetX($p[2]);
			$pdf->Cell($w[2],5,number_format($emonto,2,".",","),0,0,'R',0);
			// $monto_comisionbco= round($row['hab_prof']*($comisionbancaria / 100),2);
			// $pdf->SetX($p[3]);
			// $pdf->Cell($w[3],5,number_format($emonto2,2,".",","),0,0,'R',0);
			$linea=30;
			$pdf->SetY($linea);
			$pdf->SetX(0);
			$pdf->SetFont('Arial','B',8);			
		}
		else 
		{
			$linea+=4;
			$pdf->SetY($linea);

			$pdf->SetX($p[5]);
		    $pdf->Cell($w[5],5,'Van....',0,0,'C',0);  
			$pdf->SetX($p[6]);
			$pdf->Cell($w[6],5,number_format($emonto,2,".",","),0,0,'R',0);
			// $monto_comisionbco= round($row['hab_prof']*($comisionbancaria / 100),2);
			// $pdf->SetX($p[7]);
			// $pdf->Cell($w[7],5,number_format($emonto2,2,".",","),0,0,'R',0);
			$columna = 0;
			$pdf->AddPage();
			$linea=25;
			$pdf->SetY($linea);
			$pdf->SetX(0);
			$pdf->SetFont('Arial','B',8);
			$concepto='A';
			// $pdf->MultiCell(0,0,"Listado de Retención/Aportes al ".$fechanomina,0,C,0);
			$pdf->MultiCell(0,0,"Listado de Ayuda Solidaria al ".convertir_fechadmy($fechaaporte),0,C,0);
			$pdf->SetY($linea);
			$pdf->SetFont('Arial','',8);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0);
			$linea+=5;
			$pdf->SetY($linea);
			$pdf->SetFont('Arial','B',8);
			$pdf->SetX(30);
			
			//Cabecera

			for($i=0;$i<count($header);$i++)
			{
				$pdf->SetX($p[$i]);
			    $pdf->Cell($w[$i],7,$header[$i],0,0,'C',1);
			}
		}
	}
	$pdf->SetFont('Arial','',8);
};
$linea+=4;
$pdf->SetY($linea);

$pdf->SetX($p[4]);
$pdf->Cell($w[4],5,'Total....',0,0,'C',0);  
$pdf->SetX($p[5]);
$pdf->Cell($w[5],5,number_format($emonto,2,".",","),0,0,'R',0);
// $monto_comisionbco= round($row['hab_prof']*($comisionbancaria / 100),2);
// $pdf->SetX($p[7]);
// $pdf->Cell($w[7],5,number_format($emonto2,2,".",","),0,0,'R',0);


$archivo='ayuda_solidaria/'.$fechaaporte.'.pdf';
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
/*
$sql = "select * from sgcaretucla where fecha ='$fechaaporte'";
$result=mysql_query($sql);
if ($result > 0)
{
	$sql = "delete from sgcaretucla where fecha ='$fechaaporte'";
	$result=mysql_query($sql);
}

$sql = "insert into sgcaretucla (fecha, registros, monto, procesado, ip, archivo) values ('$fechaaporte', $registros, $emonto, now(), '$ip', '$archivo')";
// echo $sql;
$result=mysql_query($sql);
*/
$pdf->Output();
$pdf->Output($archivo);


/*
$consulta = "select * from sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
		  
$socio = mysql_fetch_array($query);
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Datos del Solicitante',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$linea+=5;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Apellido(s) '.$socio['ape_prof'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Nombre(s) '.$socio['nombr_prof'],0,0,'L',0);
$pdf->SetX(130);
$pdf->Cell(40,6,'Cédula '.$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(20,6,'Código '.$socio['cod_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Fecha Nacimiento '.convertir_fechadmy($socio['fnaci_prof']),0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(50,6,'Lugar Nacimiento '.$socio['lnaci_prof'],0,0,'L',0);
$pdf->SetX(130);
$pdf->Cell(40,6,'Estado Civil '.$socio['civil_prof'],0,0,'L',0);
//$pdf->SetX(170);
// $pdf->Cell(20,6,'Código '.$socio['cod_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Dirección '.trim($socio['dire1_prof']) . ' '.trim($socio['dire2_prof']),0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Telefono '.$socio['telf_prof'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Celular(es) '.$socio['celn_prof'] . ' / '.$socio['cel2n_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'e-mail '.trim($socio['mail_prof']) ,0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Sexo '.($socio['sexo_prof']==1?'Masculino':'Femenino'),0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Estado Civil '.$socio['civil_prof'],0,0,'L',0);


$linea+=10;
$pdf->SetX(0);
$pdf->SetY($linea);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Información Laboral',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$linea+=5;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Dependencia ' .$socio['nombr_empr'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Dirección ' .trim($socio['dire1_empr']). ' ' .trim($socio['dire2_empr']),0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Teléfono ' .$socio['tele_empr']. '    Extension '.$socio['ext_empr'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Fax '.$socio['fax_empr'],0,0,'L',0);
$pdf->SetX(100);
$pdf->Cell(40,6,'Cargo '.$socio['cargo'],0,0,'L',0);
$pdf->SetX(150);

$elcescuela=$socio['escuela'];
$sql="select codigo, nombre from escuelas where codigo = '$elcescuela'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(50,6,'Dependencia '.$fila2['nombre'],0,0,'L',0);
$eldecanato=$fila2['nombre'];

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$elcdpto=$socio['dept_prof'];
$sql="select escdpto, escuela from sgcafeyd where escdpto = '$elcdpto'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(90,6,'Departamento: ' .$fila2['escuela'],0,0,'L',0);
// $pdf->SetX(70);
// $pdf->Cell(60,6,'Ubicación '.$socio['ubic_prof'],0,0,'L',0);
// $pdf->SetX(100);
// $pdf->Cell(40,6,'Cargo '.$socio['cargo'],0,0,'L',0);
// $pdf->SetX(150);
// $pdf->Cell(50,6,'Dependencia '.$fila2['nombre'],0,0,'L',0);

$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Información Administrativa Interna',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

$linea+=5;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Tipo de Afiliado ' .$socio['tipo_prof'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(100,6,'Fecha Ingreso '.convertir_fechadmy($socio['f_ing_capu']),0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(40,6,'Estatus '.$socio['statu_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Tipo de Cuenta ' .$socio['tipo_cuenta'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(100,6,'Nro. Cuenta '.$socio['cta_nva'],0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,'% Retención '.number_format($socio['aport_prof'],2,".",","),1,0,'L',0);


$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Datos de Grupo Familiar',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Cédula','Apellidos y Nombres','Parentesco','F.Nacimiento','Edad','% Herencia');

//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
//Cabecera
    $w=array(20,60,30,40,20,20);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $pdf->Ln();
	
//Restauración de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);

//Buscamos y listamos los proveedores

$consulta = "select * from sgcaf220 where ced_afi='$cedula' order by afi_afi desc";
$query = mysql_query($consulta);
$linea+=3;

while ($row = mysql_fetch_array($query))
        {
		
		 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row["afi_afi"],0,0,'LRTB',0); 
		 $acotado = substr($row["nom_afi"], 0, 45);
         $pdf->Cell($w[1],5,$acotado,0,0,'LRTB',0);  
		 $pdf->Cell($w[2],5,$row["par_afi"],0,0,'LRTB');
		 $pdf->Cell($w[3],5,convertir_fechadmy($row["nac_afi"]),0,0,'LRTB',0);
         $pdf->Cell($w[4],5,cedad(convertir_fechadmy($row["nac_afi"])),0,0,'LRTB',0);
	     $pdf->Cell($w[5],5,number_format($row["por_afi"],2,".",","),0,0,'R',0);
/*
		 $pdf->Cell($w[0],5,$row["afi_afi"],'LRTB',0,'C'); 
		 $acotado = substr($row["nom_afi"], 0, 45);
         $pdf->Cell($w[1],5,$acotado,'LRTB',0,'L');  
		 $pdf->Cell($w[2],5,$row["par_afi"],'LRTB',0,'L');
		 $pdf->Cell($w[3],5,convertir_fechadmy($row["nac_afi"]),'LRTB',0,'L');
         $pdf->Cell($w[4],5,cedad(convertir_fechadmy($row["nac_afi"])),'LRTB',0,'R');
	     $pdf->Cell($w[5],5,number_format($row["por_afi"],2,".",","),'LRTB',0,'R');
//         $pdf->Ln();	 
/
        };
		
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->Cell(0,6,'SOLO PARA USO DEL CONSEJO DE ADMINISTRACION',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

$pdf->SetFont('Arial','',8);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Aprobado su ingreso e inclusión en Nómina desde el día _______ del mes de ___________ de __________ ',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Negado (   )   Observación___________________________________________________________________________________________ ',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'________________________________________________________________________________________________________ ',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Acta N°________________________________Fecha______________________________________________________________ ',0,0,'L',0);


$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->Cell(0,6,'CONSEJO DE ADMINISTRACION',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

$pdf->SetFont('Arial','',8);
//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Presidente(a)','Tesorero(a)','Secretario(a)','Sello');
$w=array(50,50,50,40);
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],25,'',1,0,'C',1);
$pdf->Ln();
// $linea+=5;
$pdf->SetY($linea);
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],3,$header[$i],0,0,'C',0);
$linea+=5;
$pdf->SetY($linea);
$header=array('_____________','___________','_____________','');
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],15,$header[$i],0,0,'C',0);
$pdf->Ln();
$linea+=5;
$pdf->SetY($linea);
$header=array('Firma','Firma','Firma','');
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],15,$header[$i],0,0,'C',0);

// SetAutoPageBreak();
$pdf->AddPage();
$pdf->SetX(0);

$linea=45;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"A U T O R I Z A C I O N   D E   D E S C U E N T O",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',10);
$linea+=15;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(10,0,'     Yo, ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(25);
$pdf->Cell(100,0,trim($socio['ape_prof']).', '.trim($socio['nombr_prof']),0,0,'L'); 
$pdf->SetFont('Arial','',10);
$pdf->SetX(130);
$pdf->Cell(60,0,'mayor de edad, titular de la Cédula ',0,0,'L'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(25,0,'de Identidad ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(35);
$pdf->Cell(20,0,trim($socio['ced_prof']),0,0,'L'); 
$pdf->SetFont('Arial','',10);
$pdf->SetX(60);
$pdf->Cell(85,0,'trabajador(a) Obrero(a) del Decanato o Dependencia, ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(145);
$pdf->Cell(40,0,trim($eldecanato),0,0,'L'); 
$pdf->SetX(180);
$pdf->SetFont('Arial','',10);
$linea+=10;
$pdf->SetY($linea);
$sql="select * from sgcaf100";
$query = mysql_query($sql);
$rempresa = mysql_fetch_assoc($query);
$cadena='de la '.trim($rempresa['patron_empr']).'. Autorizo al Consejo de Administración de '.trim($rempresa['nombr1_empr']).' '.trim($rempresa['nombr2_empr']).' ('.trim($rempresa['alias_empr']).'), para que proceda a descontar la cantidad del '.number_format($socio['aport_prof'],2,".",",").'% de mi salario, por concepto de cuota como socio de '.trim($rempresa['alias_empr']);
$pdf->SetX(10);
$extraer=110;
$pdf->Cell($extraer,0,substr($cadena,0,$extraer),0,0,'L'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell($extraer,0,substr($cadena,$extraer,$extraer),0,0,'L'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell($extraer,0,substr($cadena,$extraer*2,$extraer),0,0,'L'); 
$linea+=40;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'Firma Conforme',0,0,'C'); 
$linea+=20;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'________________________',0,0,'C'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'C.I.:___________________',0,0,'C'); 
$linea+=65;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'Barquisimeto: '.date('d/m/Y'),'R',0,0); 



$pdf->AddPage();
$pdf->SetX(0);

$linea=45;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"AUTORIZACION DE DOMICILIACION DE PAGO CON CARGO EN CUENTA",0,C,0);
$linea+=5;
$pdf->SetY($linea);
$pdf->MultiCell(0,0,"(PERSONA NATURAL)",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$linea+=15;
$pdf->Cell(10,0,'Señores:',0,0,'L'); 
$pdf->SetY($linea);
$linea+=5;
$pdf->Cell(10,0,'Banco Provincial S.A. Banco Universal',0,0,'L'); 
$pdf->SetY($linea);
$linea+=5;
$pdf->Cell(10,0,'Presente.-',0,0,'L'); 
$pdf->SetY($linea);
$linea+=15;
/*
$pdf->SetX(10);
$pdf->Cell(10,0,'     Yo, ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(25);
$pdf->Cell(100,0,trim($socio['ape_prof']).', '.trim($socio['nombr_prof']),0,0,'L'); 
$pdf->SetFont('Arial','',10);
$pdf->SetX(130);
$pdf->Cell(60,0,'titular de la Cédula de Identidad ',0,0,'L'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(20,0,trim($socio['ced_prof']),0,0,'L'); 
*
$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$cuento ='   Yo, '.trim($socio['ape_prof']).', '.trim($socio['nombr_prof']).' titular de la Cédula de Identidad '.trim($socio['ced_prof']).' por la presente autorizo de manera amplia y suficiente, al Banco Provincial S.A. Banco Universal, (en lo sucesivo "El Banco"),';
$cuento.='para debitar con cargo a mi cuenta Nº '.trim($socio['nro_cuenta']).', que mantengo en "El Banco", las cantidades de dinero ';
$cuento.='correspondiente al pago de los recibos de facturación que emita la ';
$cuento.=trim($rempresa['nombr1_empr']).' '.trim($rempresa['nombr2_empr']).' ('.trim($rempresa['alias_empr']).'), (en lo sucesivo denominado “La Prestadora del Servicio”), por conceptos de consumos derivados de mi afiliación al Plan  Descuento a Banco. 
Considerando que los débitos autorizados no son efectuados por “El Banco” en fecha fijas, me comprometo, a fin de que pueda ejecutarse la presente autorización, a mantener en mi referida cuenta provisión de fondos suficientes y disponibles de forma tal que “EL Banco”, pueda efectuar, con cargo a la misma, los débitos autorizados. En consecuencia, expresamente libero a “El Banco” de toda responsabilidad con respecto de aquellas facturas que no resulten pagadas mediante débito a la cuenta, por no haber habido en la misma provisión de fondos disponibles y suficientes al momento de que debió procesarse por parte de “El Banco” el pago automático correspondiente a favor de la “Prestadora del Servicio”. No obstante lo expresado, si “El Banco” llega a pagar a la “Prestadora del Servicio”. por concepto de una facturación de servicio a mi nombre una cantidad en exceso de la provisión de fondos existentes en mi cuenta, se considera esa cantidad pagada en exceso como el otorgamiento de un crédito a mi favor, por parte de “El Banco” En este supuesto, tal crédito será exigible por “El Banco” a partir del vencimiento del mes calendario en el cual “El Banco” efectúe el pago en exceso de la provisión, reservándose “El Banco” el derecho de cobrarme intereses al tipo máximo cobrado por “El Banco” por concepto de sobregiros en cuenta. No obstante si en la cuenta llegase haber fondos disponibles, (provisión), para cubrir tal crédito total o parcialmente, “El Banco” podrá proceder a imputar tales cantidades, (provisión), al pago de los intereses y de capital derivado de la obligación mencionada.
Con el otorgamiento de la presente autorización expresamente libero a “El Banco” de toda responsabilidad si algún recibo de facturación emite a mi nombre por la “Prestadora del Servicio” no resulta pagado por este mecanismo de domiciliación de pago con cargo a mi cuenta, por causas imputables a “El Banco”.
La presente autorización queda sujeta a la normativa que regula mi referida cuenta con “El Banco” y además los términos y condiciones siguientes:
1.	La revocatoria de la presente autorización deberá hacerla mediante comunicación escrita dirigida a “El Banco”, y consignada en la agencia u oficina correspondiente a mi cuenta, con por lo menos sesenta (60) días de anticipación a la fecha en que dicha revocatoria deba tener efecto. Como quiera que el plazo de sesenta(60) días se otorga en beneficio de El Banco éste podrá renunciar al mismo y dar plenos efectos a la revocatoria en cualquier momento antes de que hubiesen transcurridos sesenta(60) días sin que requiera de su parte ningún acto o aviso.
2.	La notificación de cualquier modificación o cambio que con relación la presente autorización deba hacérsele a la “Prestadora del Servicio” quedará bajo mi única responsabilidad.
3.	Para todos sus efectos derivados  y consecuencias convengo que sea la ciudad de Caracas el domicilio especial, sin perjuicio para “El Banco” de poder ocurrir a otro conforme a la Ley, si lo considera conveniente.
La presente Autorización se otorga en fecha: '.date('d/m/Y').'


																			__________________________
																			FIRMA TITULAR DE LA CUENTA';

// $pdf->Cell(0,0,$cuento,0,0,'L'); 
$pdf->MultiCell(0,5,$cuento,0,'L'); 



// $pdf->MultiCell(0,8,$cadena,1,0,'L'); 


//$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 

/*
$pdf->AddPage();
$pdf->SetX(0);
$sql="select * from sgcarepo";
$query = mysql_query($sql);
$linea+=3;

while ($reporte = mysql_fetch_array($query))
        {
		$pdf->SetY($reporte['fila']);
		$pdf->SetX($reporte['columna']);
		$tipo=($reporte['fuente']==''?'Arial':$reporte['fuente']);
		$estilo=($reporte['estilo']==''?'':$reporte['estilo']);
		$tamano=($reporte['tamano']==''?10:$reporte['tamano']);
		$pdf->SetFont($tipo,$estilo,$tamano);
		if ($reporte['estexto']=='0')		// solo texto
			$eltexto=$reporte['texto'];
		if ($reporte['estexto']=='1')	// campo
			{
				$variable = 'eltexto';
				$$eltexto=''.$reporte['campo']."['".trim($reporte['texto'])."']";
				$eltexto = $$eltexto;
//				echo '$socio["'.$reporte['texto'].'"]';
				echo ${$variable}. ' - ' .${$eltexto};
			}
		if ($reporte['estexto']=='2')	{
				$variable = 'eltexto';
				$$variable=''.$reporte['texto'];	// variable
				}
		if ($reporte['multicelda'] == 1)
			$pdf->MultiCell($reporte['alto'],$reporte['ancho'],$eltexto,$reporte['celda'],$reporte['alineacion'],$reporte['celda']);
		else 
			$pdf->Cell($reporte['ancho'],$reporte['alto'],$$eltexto,$reporte['cuadro'],0,$reporte['alineacion'],0);
// $pdf->Cell(100,0,trim($socio['ape_prof']).', '.trim($socio['nombr_prof']),0,0,'L'); 
		
        };
*




// $pdf->Cell(array_sum($w),0,'','T');	 
$pdf->Output();

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
*/
function encabeza_aportes($pdf,$linea)
{
//	return $linea;
}
?> 
