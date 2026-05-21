<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
/*
	cuando se quiera obtener informacion de sql server debe:
	1. modificar el php.ini para :
		a. habilitar la libreria php_mssql
		b. habilitar la ruta donde estan las librerias
		c. copiar la libreria php_mssql.dll en ext y/o en windows/system32
		d. reiniciar el apache
	2. en sql-server tener el permiso del usuario a conectar
	3. copiar la libreria ntwdblib.dll en windows/system32
	4. reiniciar
*/
// require("final.php");
$Usuario="cappoucl_datos";
$Password="t3wp0r@1";
$Servidor="65.110.52.32";
$BasedeDatos="cappoucl_datos";
echo 'sincronizacion de f200 <br>';
$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexión con el servidor, inténtalo más tarde.</div>");
mysql_select_db($BasedeDatos, $link);
if (mysql_select_db($BasedeDatos, $link)) {




/*
	$cuantos=0;
//	$conectID = mssql_connect("127.0.0.1","jhernandez","nene14");
	$conectar=(mssql_connect("192.168.5.2","jhernandez","nene14")) or die ('No pude<br>'.mssql_get_last_message());
	if (0 ==  0){
	mssql_select_db("fastcard"); 
	$sqls='select top 3 convert(varchar(10),upd_dat,112) as fecha from fc4000 where (mysql = 0) group by convert(varchar(10),upd_dat,112) order by fecha';
	$results=mssql_query($sqls);
	while($rsqls=mssql_fetch_assoc($results)) {
		echo $rsqls['fecha'].'<br>';
		$lafecha=$rsqls['fecha'];
//		$lafecha=explode('/',$rsqls['fecha']);
//		$sqls2="select * from fc4000 where (datepart('yyyy',fec_emi) = ".$lafecha[2] ." and datepart('mm',fec_emi) = ".$lafecha[1] ." and datepart('dd',fec_emi) = ".$lafecha[0] .") order by registro";
		$sqls2="SET DATEFORMAT ymd\n";
//		$sqls2.="go \n";
		$sqls2.="select *, convert(varchar(10),fec_emi,103) as f1, convert(varchar(10),ult_emi,103) as f2, convert(varchar(10),upd_dat,103) as f3 from fc4000 WHERE (convert(varchar(10),upd_dat,112) = '".$lafecha."') and (mysql = 0) order by registro \n";
//		echo $sqls2;
		$result2s=mssql_query($sqls2);
		$tiempo = mssql_num_rows($result2s);
		if ($tiempo < 30)
			$tiempo = 30;
		set_time_limit  ($tiempo);
		echo 'Registros a actualizar '.mssql_num_rows($result2s).'<br>';
//		set_time_limit  (120);
		while($row=mssql_fetch_assoc($result2s)) {
//			echo '...'.$rsql2s['tar_emi'].'-'.$rsql2s['registro'].'<br>';
			$registro=$row['registro'];
			$sql="select tar_emi from fc4000 where registro='$registro'";
			$registros=0;
			$result=mysql_query($sql) or die ($mysql);
			$registros=mysql_num_rows($result);
			if ($registros == 1) {
				$sql="delete from fc4000 where registro = '$registro'";
				$result=mysql_query($sql) or die ($mysql);
			}
			echo $row['tar_emi']. ' - '; // .$row['f1'].'<br>';
//				$f1=$row['fec_emi'];
			$f1=$row['f1'];
			$f1=explode("/",$f1);
			$f1=substr($f1[2],0,4)."-".$f1[1]."-".$f1[0]; // substr($a[0],2,2);

//				$f2=$row['ult_emi'];
			$f2=$row['f2'];
			$f2=explode("/",$f2);
			$f2=substr($f2[2],0,4)."-".$f2[1]."-".$f2[0]; // substr($a[0],2,2);

			$f3=$row['f3'];
			$f3=explode("/",$f3);
			$f3=substr($f3[2],0,4)."-".$f3[1]."-".$f3[0]; // substr($a[0],2,2);
			$nombre = $row['nom_emi'];
			$cadena=str_replace('"','_',$nombre);
			$cadena=str_replace("'",'_',$cadena);

//			$cadena = preg_replace('/<(.*)>/\"', '', $nombre); 
//			$cadena = preg_replace("\'", '', $nombre); 
//			echo $cadena.'<br>';
			$mysql="insert into fc4000 (
				emp_emi, tar_emi, cla_emi, env_emi, 
				nom_emi, ced_emi, rel_emi, tip_emi, 
				res_dia, sta_emi, pri_emi, ver_emi, 
				val_emi, npe_emi, mon_emi, dia_emi, 
				fec_emi, ult_emi, com_emi, imp_emi,
				registro, upd_dat) values (
				'".$row['emp_emi']."', '".$row['tar_emi']."', '".$row['cla_emi']."', '".$row['mysql']."', 
				'".$cadena."', '".$row['ced_emi']."', '".$row['rel_emi']."', '".$row['tip_emi']."', 
				'".$row['res_dia']."', '".$row['sta_emi']."', '".$row['pri_emi']."', '".$row['ver_emi']."',
				'".$row['val_emi']."', '".$row['npe_emi']."', '".$row['mon_emi']."', '".$row['dia_emi']."', 
				'".$f1."', '".$f2."', '".$row['com_emi']."', '".$row['imp_emi'].
				"', '".$row['registro']."', '".$f3."')"; 
//			echo $mysql.'<br>';
//			if (0 == 0); // (!mysql_query($mysql)) die ($mysql);
			if (!mysql_query($mysql)) die ($mysql);
			else {
				// actualizar registro en sql
				$mssql="update fc4000 set mysql = 1 where registro = ".$registro;
//				echo $mssql.'<br>';
				$resultms=mssql_query($mssql);
				$cuantos++;
			}
//	echo $mysql.'<br>';
		}
		echo '<br>';	
		
	}
//	echo 'llegue';
	}
}
echo 'Registros actualizados '.$cuantos.'<br>';
echo 'finalizado';

/*
$buscar=$_GET['elrif'];
$conectID = mssql_connect("192.168.5.2","jhernandez","nene14");
mssql_select_db("fastcard"); 
$mssql="select * from fc1000 where rif_emp like '%$buscar%'";
// echo $mssql;
$result=mssql_query($mssql,$conectID); 
$row=mssql_fetch_array($result);
$laletrarif		= substr($row['rif_emp'],0,1); 
$rif			= substr($row['rif_emp'],2,12); 
$nombre			= $row['nom_emp']; 
$ladireccionh1	= substr($row['dir_emp'],0,40); 
$ladireccionh2	= substr($row['dir_emp'],40,40); 
$eltelefonoh	= $row['tel_emp'];
$elemail		= $row['mai_emp'];
$comision		= $row['com_emp'];
$contactorrhh	= $row['con_emp'];
$eltelefonorrhh	= $row['cel_emp'];
$contactoadm	= $row['aut_emp'];
mssql_close($conectID); 

//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
//echo "<cuota>".$interes."</cuota>";
echo "<laletrarif>".$laletrarif."</laletrarif>";
echo "<rif>".$rif."</rif>";
echo "<nombre>".$nombre."</nombre>";
echo "<ladireccionh1>".$ladireccionh1."</ladireccionh1>";
echo "<ladireccionh2>".$ladireccionh2."</ladireccionh2>";
echo "<eltelefonoh>".$eltelefonoh."</eltelefonoh>";
echo "<elemail>".$elemail."</elemail>";
echo "<comision>".$comision."</comision>";
echo "<contactorrhh>".$contactorrhh."</contactorrhh>";
echo "<eltelefonorrhh>".$eltelefonorrhh."</eltelefonorrhh>";
echo "<contactoadm>".$contactoadm."</contactoadm>";
echo "</resultados>";
*/	
?>