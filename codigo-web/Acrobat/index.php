<html>
<body >

<script type="text/javascript" language="javascript" src="calendario.js"></script>


<form name="formulario" action="actionEditarFeria.php" onSubmit="" method="POST">
	Fecha  <input type="text" name="fecha_inicio" value="dd/mm/aaaa"  onclick="mostrarCalendario('fecha_inicio','11','2008')"/><br/>
</form>
<div  id="calendario"  style="visibility:hidden;position:relatiave;"  class="arrastrable" ></div>
</body>
</html>

