  <form action="comprobar.php" method="post">
  <table width="500"><tr><td>
  <tr><td><b>Nombre: </b></td><td><input type="text" name="nombre" size="20" maxlength="30"></td></tr>
  <tr><td><b>Email: </b></td><td><input type="text" name="email" size="20" maxlength="100"></td></tr>
  <tr><td><b>Comentario: </b></td><td><input type="text" name="comentario" size="20" maxlength="200"></td></tr>
  </table>
  <p align="center">
  <b>*Código de confirmación:</b> <img src="captcha.php" /> <input type="text" name="codigo" size="10">
  </p>
  <br><br>
  <input type="reset" value="    Borrar    "> <input type="submit" value="    Enviar    ">
  </form>