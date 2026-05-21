<?php

$_SESSION['c'] = 1;
if ($_COOKIE["c"]){$_SESSION['c'] = $_COOKIE["c"];}

if ($_POST['color']) {
	$c = urlencode(stripslashes($_POST['color']));
	setcookie("c", $c, time() + 60*60*24*365);
	$_SESSION['c'] = $_POST['color'];
}

$c = $_SESSION['c'];

?>