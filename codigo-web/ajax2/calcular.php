<script type="text/javascript" src="ajax.js"></script>
<form name="form1" id="form1" method="post" enctype="multipart/form-data">
<table width="421" height="333" border="0" align="center">
<tr>
<td height="25" colspan="2">Introduzca el Tipo de producto:
<label>
<input type="text" name="tipo_producto" id="tipo_producto" value="<?php print $tipo_producto ?>">
</label></td>
</tr>
<tr>
<td height="25" colspan="2">Introduzca la Cantidad:
<label>
<input type="text" name="cantidad" id="cantidad" value="<?php print $cantidad ?>">
</label></td>
</tr>
<tr>
<td colspan="2">Seleccione el Color:
<label>
<select name="color" id="color">
<option selected value="0"></option>
<option value="1">Un color</option>
<option value="2">Dos colores</option>
<option value="3">Tres colores</option>
<option value="4">Full color</option>
</select>
<a title="Calcular" href="javascript:Cargarcontenido('mostrar.php', 'c=3', 'form1', 'contenido2')">Calcular</a>
</label></td>
</tr>
</table>
</form>
<br />
<div id="contenido2"></div>