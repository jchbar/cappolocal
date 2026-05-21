<?
/***************************************************************************

botproof email v3.1
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


Changelog:

v3.1 06-23-04
	fixed a bug when register_globals is off
	added: code displayed is now rotated at a random angle
	added truetype font support (depends on PHP installation, code version only)

v3.0 06-28-03
	major update
	limited the number of attempts allowed (default 5) (thanks to Milan for the idea)
	added random lines in the graphics to confuse OCR-enabled bots
	'back' links now always point to the right page  
	completely rearranged the code into one class
	now easier to use and integrate into your own pages

v2.0 07-05-02
	new challenge: click a colored rectangle

v1.0	
	initial release



***************************************************************************/

/**
 * The Botproof Email base class. Any challenges are derived from this class.
 * @abstract
 * @author Kai Blankenhorn <kaib@bitfolge.de>
 */
class BotproofEmail {
	
	/**
	 * how many attemps to grant the user to master the challenge
	 * @access public
	 */
	var $maxTries = 5;

	/**
	 * the email address the users is forwarded to upon mastering the challenge
	 * @access public
	 */
	var $email = "";

	/**
	 * the background color of the generated image. Specified as a 3 element array for R, G and B values. Defaults to white.
	 * @access public
	 */
	var $background;

	/**
	 * the foreground color of the generated image. Specified as a 3 element array for R, G and B values. Defaults to dark blue.
	 * @access public
	 */
	var $foreground;

	/**
	 * the width of the generated image
	 * @access private
	 */
	var $imageX = 0;

	/**
	 * the height of the generated image
	 * @access private
	 */
	var $imageY = 0;

	/**
	 * the background color of the generated image after it has been allocated. Specified as an integer.
	 * @access private
	 */
	var $bgAllocated;

	/**
	 * the foreground color of the generated image after it has been allocated. Specified as an integer.
	 * @access private
	 */
	var $fgAllocated;
	

	/**
	 * Constructor of BotproofEmail. Initializes foreground and background colors.
	 * @access public
	 */
	function BotproofEmail() {
		$this->background = Array(255,255,255);
		$this->foreground = Array(0,0,128);
	}
	
	/**
	 * Reset the Botproof Email session data. Usually only done at the beginning.
	 * @access public
	 * @abstract
	 */
	function reset() {
	}
	
	
	/**
	 * Generate an image containing the challenge and write it to standard out.
	 * @access private
	 * @abstract
	 */
	function generateImage() {
	}	


	/**
	 * Prepare the image the challenge is drawn on.
	 * @access private
	 * @return image a GDLib image resource
	 */
	function _createImage() {
		Header("Content-type: image/png");
		if (function_exists("imagecreatetruecolor")) {
			$im = imagecreatetruecolor ($this->imageX, $this->imageY);
		} else {
			$im = imagecreate ($this->imageX, $this->imageY)
				or die ("Cannot Initialize new GD image stream");
		}
		if (function_exists("imageantialias")) {
			imageantialias($im, true);
		}
		$this->bgAllocated = imagecolorallocate($im, rand(224,256), rand(224,256), rand(224,256));
		$this->fgAllocated = imagecolorallocate($im, $this->foreground[0], $this->foreground[1], $this->foreground[2]);
		imagefill($im, 0, 0, $this->bgAllocated);
		return $im;		 
	}
	

	/**
	 * Generate some random lines on the image.
	 * @param image $im a GDLib image resource to modify
	 * @param int $amount (optional) the number of lines to draw
	 * @param char $direction the direction of the lines ('x'=horizontal, 'y'=vertical)
	 * @access private
	 */
	function _generateImageNoise($im,$amount=5,$direction="y") {
		$x = imagesx($im);
		$y = imagesy($im);
		for ($i=0;$i<$amount;$i++) {
			$color = imagecolorallocate($im, rand(64,192), rand(64,192), rand(64,192));
			if ($direction=="y") {
				$r1 = rand(0,$x);
				imageline($im, $r1, 0, $r1+rand(-5,5), $y, $color);
			} else {
				$r1 = rand(0,$y);
				imageline($im, 0, $r1, $x, $r1+rand(-5,5), $color);
			}
		}
	}
	

	/**
	 * Print out the initial input form for this challenge.
	 * @param string $imageScript the script / URL to call which does the image output
	 * @param string $checkScript the script / URL to call which checks the user's input
	 * @access public
	 * @abstract
	 */
	function getForm($imageScript, $checkScript) {
	}
	

	/**
	 * Print out an error message which tells the user he has not mastered the challenge.
	 * @access public
	 * @abstract
	 */
	function getError() {
	}
	

	/**
	 * Print out an error message which tells the user he has not mastered the challenge
	 * and doesn't have any attempts left.
	 * @access public
	 * @abstract
	 */
	function getDenied() {
	}
	

	/**
	 * Calculate if the user input matches the values stored in the session, that is if
	 * he has mastered the challenge.
	 * @access private
	 * @abstract
	 */
	function _doCheck() {
	}
	
	function check() {
		if ($_SESSION["tries"]>$this->maxTries) {
			echo $this->getDenied();
		} else {
			if ($_SESSION["tries"]=="") {
				$_SESSION["tries"] = 1;
			} else { 
				$_SESSION["tries"]++;
			}
			if($this->_doCheck()) {
				$_SESSION["tries"] = 0;
				Header("Location: mailto:".$this->email);
			} else {
				if ($_SESSION["tries"]>$this->maxTries) {
					echo $this->getDenied();
				} else {
					echo $this->getError();
				}					
			}
		}
	}
	
}


/**
 * A Botproof Email challenge that uses a generated code that has to be entered into a form.
 * For method documentation see BotProofEmail.
 * @author Kai Blankenhorn <kaib@bitfolge.de>
 */
class BotproofEmailCode extends BotproofEmail {
	
	var $code = "";
	var $font = "";
	
	function BotproofEmailCode() {
		$this->BotproofEmail();
		$this->code = $_SESSION["safeMailCode"];
		$this->imageX = 120;
		$this->imageY = 75;
	}
	
	function reset() {
		$_SESSION["safeMailCode"] = null;
		$code = null;
	}
	
	function generateImage() {
		$_SESSION["safeMailCode"] = "";
		$chars = "ABCDEFGHIJKLMNOPRSTUVWXYZ";
		
		for ($i=0;$i<rand(6,8);$i++) {
			$_SESSION["safeMailCode"].= $chars[rand(0,strlen($chars)-1)];
		}
		$im = $this->_createImage();
		$maxAngle = 30;
		$angle = rand(-100*$maxAngle,100*$maxAngle)/100.0;
		
		if (function_exists("imagettftext") && $this->font!="") {
			$bbox = imagettfbbox(12, $angle, $this->font, $_SESSION["safeMailCode"]);
			$minX = min($bbox[0], $bbox[2], $bbox[4], $bbox[6]);
			$maxX = max($bbox[0], $bbox[2], $bbox[4], $bbox[6]);
			$minY = min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
			$maxY = max($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
			$x = max(0, ($bbox[0]-$minX)+rand(0, $this->imageX - ($maxX-$minX)));
			$y = max(0, ($bbox[1]-$minY)+rand(0, $this->imageY - ($maxY-$minY)));
			imagettftext($im, 12, $angle, $x, $y, $this->fgAllocated, $this->font, $_SESSION["safeMailCode"]);
		} else {
			imagestring($im, 5, rand(0,$this->imageX-65), rand(0,$this->imageY-15), $_SESSION["safeMailCode"], $this->fgAllocated);
			if (function_exists("imagerotate")) {
				$im = imagerotate($im, $angle, $this->bgAllocated);
			}
		}
		
		$this->_generateImageNoise($im, $this->imageX/15, "x");
		$this->_generateImageNoise($im, $this->imageY/15, "y");
		imagepng($im);
	}
	
	function getForm($imageScript, $checkScript) {
		$glueChar = strpos($imageScript,"?")===false ? "?" : "&";

		$imageLink = $imageScript.$glueChar."PHPSESSID=".session_id();
		$checkLink = $checkScript.$glueChar."PHPSESSID=".session_id();
		$form = "
			<img src=\"$imageLink\" width=\"".$this->imageX."\" height=\"".$this->imageY."\" alt=\"Botproof Email\" border=\"0\"><br>
			This is a spam bot challenge. Please enter the code from the image above in this text field to get to the desired email address.
			<form action=\"$checkLink\" method=\"post\">
			Botproof Email code: <input type=\"text\" name=\"code\"> &nbsp;&nbsp; <input type=\"submit\">
			</form>
		";
		return $form;
	}
	
	function getError() {
		return "
			<html><head><title>Sorry, wrong code</title></head><body>
			Sorry, you have to enter the code from the image in order to obtain the email address.<br>
			<a href=\"".$_SERVER["HTTP_REFERER"]."\">back</a>
			</body></html>
		";
	}
	
	function getDenied() {
		return "
			<html><head><title>Access denied</title></head><body>
			Sorry, you failed to enter the correct code ".$this->maxTries." times.<br>
			Please try again later.<br>
			<a href=\"".$_SESSION["HTTP_REFERER"]."\">back</a>
			</body></html>
		";
	}
	
	function _doCheck() {
		return
			$_SESSION["safeMailCode"] AND
			$_SESSION["safeMailCode"]==$_POST["code"];
	}
}


/**
 * A Botproof Email challenge that uses a small box that has to be clicked..
 * For method documentation see BotProofEmail.
 * @author Kai Blankenhorn <kaib@bitfolge.de>
 */
class BotproofEmailBox extends BotproofEmail {
	
	// all variables are private
	var $boxPosX;
	var $boxPosy; 
	var $boxX = 20;
	var $boxY = 20;
	
	function BotproofEmailBox() {
		$this->BotproofEmail();
		$this->boxPosX = $_SESSION["x"];
		$this->boxPosY = $_SESSION["y"];
		$this->imageX = 300;
		$this->imageY = 200;
	}
	
	function reset() {
		$_SESSION["x"] = null;
		$_SESSION["y"] = null;
		$this->boxPosX = null;
		$this->boxPosY = null;
	}
	
	function generateImage() {
		$_SESSION["x"] = rand(0,$this->imageX-$this->boxX);
		$_SESSION["y"] = rand(0,$this->imageY-$this->boxY);
		
		$text_color = 0;
		
		$im = $this->_createImage();
		imagefilledrectangle($im, $_SESSION["x"], $_SESSION["y"], $_SESSION["x"]+$this->boxX, $_SESSION["y"]+$this->boxY, imagecolorallocate($im, 128+rand(-32,32), 128+rand(-32,32), 128+rand(-32,32)));
		$this->_generateImageNoise($im, $this->imageX/6, "x");
		$this->_generateImageNoise($im, $this->imageY/6, "y");
		imagepng($im);
	}
	
	function getForm($imageScript, $checkScript) {
		$glueChar = strpos($imageScript,"?")===false ? "?" : "&";

		$imageLink = $imageScript.$glueChar."PHPSESSID=".session_id();
		$checkLink = $checkScript.$glueChar."PHPSESSID=".session_id();

		$form = "
			This is a spam bot challenge. Please click the box to get to the desired email address.
			<form action=\"$checkLink\" method=\"post\">
			<input type=\"hidden\" name=\"type\" value=\"box\">
			<input type=\"image\" src=\"$imageLink\" alt=\"Botproof Email\" title=\"click the box to get to the desired email address\"width=\"".$this->imageX."\" height=\"".$this->imageY."\" border=\"0\"><br>
			</form>
		"; 
		return $form;
	}
	
	function getError() {
		return "
			<html><head><title>Sorry, missed.</title></head><body>
			Sorry, you have to click the blue box in the image in order to obtain the email address.<br>
			<a href=\"".$_SERVER["HTTP_REFERER"]."\">back</a>
			</body></html>
		";
	}
	
	function getDenied() {
		return "
			<html><head><title>Access denied</title></head><body>
			Sorry, you failed to hit the box ".$this->maxTries." times.<br>
			Please try again later.<br>
			<a href=\"".$_SESSION["HTTP_REFERER"]."\">back</a>
			</body></html>
		";
	}
	
	function _doCheck() {
		return
			$_POST["x"]>=$_SESSION["x"] AND 
			$_POST["x"]<=$_SESSION["x"]+$this->boxX AND
			$_POST["y"]>=$_SESSION["y"] AND 
			$_POST["y"]<=$_SESSION["y"]+$this->boxY;
	}
}


?>