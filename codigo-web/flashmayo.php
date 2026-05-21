<?php
// enviar email
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
//$titulo = "Notificacion de Deposito p/Recarga de Plastico (PRUEBA PARA SITIO WEB)\n";
$titulo = "Notificacion para prestamos especiales (FLASH) \n";

$cuerpo = 'Estimado Socio(a): <br><br>';
$cuerpo.= 'El motivo del presente es para informarle que se encuentran disponibles los  <br>';
$cuerpo.= '<strong>PRESTAMOS ESPECIALES (FLASH)</strong> segun las condiciones especificadas en la  <br>';
$cuerpo.= '<strong>Circular Nro. 011-2011 de fecha  29 de Abril de 2011. </strong> <br><br>';
$cuerpo.= 'La misma puede ser visualizada en nuestra pagina <a href="http://www.cappoucla.org.ve"> www.cappoucla.org.ve</a> en la  <br>';
$cuerpo.= 'Seccion <strong>Circulares</strong> o en la siguiente direccion  <br>';
// $cuerpo.= 'http://www.cappoucla.org.ve/circulares.php <br><br>';
$cuerpo.= '<a href="http://www.cappoucla.org.ve/circulares.php" /a>http://www.cappoucla.org.ve/circulares.php</a><br><br>';
//	echo "<a target=\"_blank\" href=\"monmutpdf1.php?fechadescuento=$fechadescuento\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Listados de Descuentos</a>"; 	
//.................
	
# genera el cuerpo del mensaje
//mando el correo...

$to= 'info@cappoucla.org.ve'; // 'j.hernandez@heros.com.ve';
$bcc='juan.hernandez@heros.com.ve, juan.carlos.hernandez.barazarte@gmail.com';
require("../final.php");
$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
mysql_select_db('cappoucl_sica', $link);
$sql='select email from acceso order by email';
$result=mysql_query($sql);
$bcc='';
$cuantos=0;
while ($registro = mysql_fetch_assoc($result))
{
	$bcc.=$registro['email']. ',';
	$cuantos++;
}
$l=strlen($bcc);
$bcc=substr($bcc,0,$l-1);


/*
$enviado='From: info@fastcard.com.ve' . "\r\n" .
		'Reply-To: info@fastcard.com.ve' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
//			"multipart / mixto; boundary = \" PHP-mixto ". $random_hash." \ "; 
*/
//--------------------
/*
	$elarchivo="ordenes/".$archivo.".pdf";
	$strresume_name=$elarchivo; // $_FILES["ordenes/0003-20100901-G0002.pdf"]["name"];
	$strresume_type="application/octet-stream"; // $_FILES['ordenes/'.$archivo.'.pdf']["type"];
	$strresume_size=filesize($elarchivo); //  $_FILES['ordenes/'.$archivo.'.pdf']["size"];
	$strresume_temp=$_FILES['ordenes/'.$archivo.'.pdf']["tmp_name"];
*/
	if (0==0) // ($strresume_type=="application/octet-stream" or $strresume_type=="text/plain" or $strresume_type=="application/msword")
	{
		$message= $cuerpo;    // MAIL SUBJECT
		$subject = $titulo; // "Mail with doc file attachment";
	    // TO MAIL ADDRESS
   
		/*
    	// MAIL HEADERS
                       
	    $headers  = "MIME-Version: 1.0\n";
    	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	    $headers .= "From: Name <name@name.com>\n";

		*/

	    // MAIL HEADERS with attachment
		$num = md5(time());
   
        //Normal headers

		$headers  = "From: info@cappoucla.org.ve"."\r\n";
//		$headers  .= "MIME-Version: 1.0"."\r\n";
//		$headers  .= "Content-Type: multipart/mixed; ";
//		$headers  .= "boundary=".$num."\r\n";
//		$headers  .= "--$num"."\r\n";

        // This two steps to help avoid spam   

		$headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">\r\n";
		$headers .= "X-Mailer: PHP ".phpversion()."\r\n";         

        // With message
       
		$headers .= "BCC: $bcc "."\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1"."\r\n";
		$headers .= "Content-Transfer-Encoding: 8bit"."\r\n\n";
//		$headers .= "".$message."\n";
//		$headers .= "--".$num."\n"; 

        // Attachment headers

		// SEND MAIL
		  
	$ok = @mail($to, $subject, $message, $headers);
// echo $bcc;   

	if($ok) { 
		echo "<br>$cuantos Correo(s) enviado(s)";
	} 
	else { 
	echo "Lo sentimos pero el correo no pudo ser enviado. Por favor regrese y vuelve a intentarlo!";
	} 

}

?>
