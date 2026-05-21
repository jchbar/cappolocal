<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Calculadora calculadora.org</title>
<link rel="stylesheet" type="text/css" href="f21_archivos/calculator_zf4.css">

<script type="text/javascript" language="JavaScript" src="f21_archivos/calculator_zf3.js"></script></head><body>


<center><br/>
<h1>Calculadora</h1>
<br/>
<script type="text/javascript"><!--
google_ad_client = "pub-6362983403566814";
google_alternate_color = "FFFFFF";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text";
google_ad_channel = "2225274959";
google_ui_features = "rc:0";
//-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/>
<table width="300px"><tr><td align="center">
<div class="calculadora">
<form name="calculator" action="*">
	<table cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td colspan="5">
				<div class="numeric-display-holder">
					<div id="lcd-symbols">&nbsp;</div>
					<input value="0" class="n_display" name="expr" onclick="blur(this)" type="text">
					</div>
			</td>
		</tr>
		<tr>
		</tr>
		
		<tr>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(7);blur(this)">7</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(8);blur(this)">8</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(9);blur(this)">9</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter('/');blur(this)">÷</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="special_button" title="Clear [Keyboard: 'Del']" onclick="clearDisplay();blur(this)">C</a>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(4);blur(this)">4</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(5);blur(this)">5</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(6);blur(this)">6</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter('*');blur(this)">×</a>
				</div>
			</td>
			<td>
				<!-- <div class="button_border">
					<div class="bugfix"></div>&nbsp;
					<a href="#" class="button" title="Store exchange rate (&#8364; per $) [Keyboard: 'R']" onclick="set_rate();blur(this)"> ? </a>
				</div> -->
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(1);blur(this)">1</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(2);blur(this)">2</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(3);blur(this)">3</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter('-');blur(this)">-</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div>
					<!-- Fixing IE bug -->
					<a href="#" class="button" title="Convertir a Euros [Keyboard: 'T']" onclick="to_rate();blur(this)">&euro;</a>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter(0);blur(this)">0</a>
				</div></td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter('.');blur(this)">·</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="calc();blur(this)">=</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div><!-- Fixing IE bug -->
					<a href="#" class="button" onclick="enter('+');blur(this)">+</a>
				</div>
			</td>
			<td>
				<div class="button_border">
					<div class="bugfix"></div>
					<!-- Fixing IE bug -->
					<a href="#" class="button" title="Convertir a pesetas [Keyboard: 'Y']" onclick="from_rate();blur(this)">pts</a>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<!-- <a class="carpe" style="margin: 4px; font-size: 10px;" href="http://carpe.ambiprospect.com/projects/" title="CARPE" target="_blank" onclick="blur(this)">Calculator F 2.1 © CARPE Design</a> -->
			</td>
		</tr>
	</tbody></table>
</form>
<script>setCookie("zf_rate", "166.386")</script>
</div>

</td>
<script type="text/javascript"><!--
google_ad_client = "pub-6362983403566814";
google_alternate_color = "FFFFFF";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text";
//2007-10-06: calculadora.org
google_ad_channel = "2225274959";
google_ui_features = "rc:0";
//-->
<br />
</body>
</html>