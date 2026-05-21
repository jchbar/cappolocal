
<?php
include 'calcular_presupuesto.php';
?>
<form name="form2" id="form2" method="post" enctype="multipart/form-data">
<table width="390" height="154" border="0" align="center">
<tr>
<td height="27">Sutotal:
<label>
<input type="text" name="subtotal" value="<?php printf("%.2f",$subtotal); ?>">
</label></td>
</tr>
<tr>
<td height="30">total:
<label>
<input type="text" name="total" value="<?php printf("%.2f",$total); ?>">
</label></td>
</tr>
</table>
</form>