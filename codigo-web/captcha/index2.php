<?php
   require_once('captcha.class.php');
   $captcha = new Captcha;
   $captchaImage = $captcha->create();

   //verificamos si variables POST estan declaradas
   if(isset($_POST[$captcha->captchaInputName])){

	//verificamos el captcha
	$verified = $captcha->verify($_POST[$captcha->captchaInputName]);
	if($verified){
		$message='Correcto';
	}else{
		$message='Incorrecto';
	}
   }
?>
<html>
<head>
<title>Captcha</title>
</head>

<body>
<?php
	if(isset($message)){
		echo $message;
	}else{
?>
<form name="formulario" method="post" action="index2.php">
	<?php echo $captchaImage; ?>
  	<input type="submit" value="Verificar" />
<?php } ?>
</form>
</body>
</html> 