<?
/***************************************************************************

botproof email v3.0
(c) Kai Blankenhorn
www.bitfolge.de/en
kaib@bitfolge.de


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

****************************************************************************


This is a usage example for botproofemail.class.php


***************************************************************************/

require("botproofemail.class.php");

session_start();

$type = $_GET["type"];
if ($type=="") $type="box";

switch($phase) {
	case "":
		if ($_SESSION["HTTP_REFERER"]=="")
			$_SESSION["HTTP_REFERER"] = $_SERVER["HTTP_REFERER"];

		unset($_SESSION["bp"]);
		
		switch ($type) {
			case "box":
				$bp = new BotproofEmailBox();
				break;
			case "code":
				$bp = new BotproofEmailCode();
				// setting the font for truetype fonts
				// you have to supply your own font for this
				$bp->font = dirname(__FILE__)."/IDidThis.ttf";
				break;
			default:
				$bp = new BotproofEmailBox();
				break;
		}
		$form = $bp->getForm($PHP_SELF."?phase=generate",$PHP_SELF."?phase=check");
		$bp->email = "website@bitfolge.de";
		
		$bp->foreground = Array(0,0,64);

		$_SESSION["bp"] = $bp;
		
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
		
		<html>
		<head>
			<title>botproof email form</title>
		</head>
		
		<body>
		<?echo $form?>
		<a href="<?echo $_SESSION["HTTP_REFERER"]?>">click here to go back to where you came from</a>
		</body>
		</html>
		<?
		break;
		
		
	case "generate":
		$bp = $_SESSION["bp"];
		$bp->generateImage();
		break;
		
		
	case "check":
		$bp = $_SESSION["bp"];
		$bp->check();
		break;
}
die();
?>