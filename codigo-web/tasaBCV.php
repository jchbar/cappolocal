<?php
include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
    include("noempresa.php");
    exit;
}

$accion = $_GET['accion'];
$id = $_GET['id'];

// Logic for Add/Edit/Delete
if ($accion == 'Anadir1') {
    extract($_POST);
    $fecha_db = convertir_fecha($fecha);
    $sql = "INSERT INTO sgcatasa (fecha, montobs) VALUES ('$fecha_db', '$montobs')";
    mysql_query($sql) or die("Error al añadir tasa: " . mysql_error());
    $accion = "";
}

if ($accion == 'Editar1') {
    extract($_POST);
    $fecha_db = convertir_fecha($fecha);
    $sql = "UPDATE sgcatasa SET fecha='$fecha_db', montobs='$montobs' WHERE id='$id'";
    mysql_query($sql) or die("Error al editar tasa: " . mysql_error());
    $accion = "";
}

if ($accion == 'Borrar1') {
    $sql = "DELETE FROM sgcatasa WHERE id = '$id'";
    mysql_query($sql) or die("Error al borrar tasa: " . mysql_error());
    $accion = "";
}
?>

<body <?php if (!$bloqueo) { echo "onload=\"foco('montobs')\""; } ?>>

<?php
include("arriba.php");
include("menusizda.php");
?>

<div id="contenido" style="padding: 20px;">
    <h2>Tasa BCV (Bs/USD)</h2>

    <?php if (!$accion) { ?>
        <p>[ <a href="tasaBCV.php?accion=Anadir">Nueva Tasa</a> ]</p>
        <table class="basica hover" width="500">
            <tr>
                <th width="80">Acciones</th>
                <th width="150">Fecha</th>
                <th>Monto (Bs/USD)</th>
            </tr>
            <?php
            $sql = "SELECT * FROM sgcatasa ORDER BY fecha DESC";
            $rs = mysql_query($sql);
            while ($row = mysql_fetch_assoc($rs)) {
                $fecha_dmy = convertir_fechadmy($row['fecha']);
                echo "<tr>";
                echo "<td class='centro'>";
                echo "<a href='tasaBCV.php?accion=Editar&id=" . $row['id'] . "'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' /></a> ";
                echo "<a href='tasaBCV.php?accion=Borrar1&id=" . $row['id'] . "' onclick=\"return confirm('¿Está seguro de eliminar esta tasa?')\"><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' /></a>";
                echo "</td>";
                echo "<td class='centro'>" . $fecha_dmy . "</td>";
                echo "<td class='dcha'>" . number_format($row['montobs'], 3, ',', '.') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php } ?>

    <?php if ($accion == "Anadir") { 
        $hoy = date("d/m/Y");
    ?>
        <form action="tasaBCV.php?accion=Anadir1" name="form1" method="post">
            <fieldset style="width: 400px;">
                <legend>Registrar Nueva Tasa</legend>
                <table border="0" cellpadding="5">
                    <tr>
                        <td>Fecha:</td>
                        <td>
                            <input type="hidden" name="fecha" id="fecha" value="<?php echo $hoy; ?>"/>
                            <span style="background-color: #ff8; cursor: pointer; padding: 2px 5px; border: 1px solid #ccc;"
                                  onmouseover="this.style.backgroundColor='#ff0';"
                                  onmouseout="this.style.backgroundColor='#ff8';"
                                  id="show_fecha" 
                            ><?php echo $hoy; ?></span>
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField     :    "fecha",
                                    ifFormat       :    "%d/%m/%Y",
                                    displayArea    :    "show_fecha",
                                    daFormat       :    "%d/%m/%Y",
                                    align          :    "Tl",
                                    singleClick    :    true,
                                    weekNumbers    :    false
                                });
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <td>Monto Bs/USD:</td>
                        <td><input type="text" name="montobs" id="montobs" size="15" maxlength="15" /></td>
                    </tr>
                </table>
                <br />
                <input type="submit" value="Grabar Tasa" />
                <input type="button" value="Cancelar" onclick="window.location='tasaBCV.php'" />
            </fieldset>
        </form>
    <?php } ?>

    <?php if ($accion == "Editar") { 
        $sql = "SELECT * FROM sgcatasa WHERE id = '$id'";
        $rs = mysql_query($sql);
        $row = mysql_fetch_assoc($rs);
        $fecha_dmy = convertir_fechadmy($row['fecha']);
    ?>
        <form action="tasaBCV.php?accion=Editar1&id=<?php echo $id; ?>" name="form1" method="post">
            <fieldset style="width: 400px;">
                <legend>Editar Tasa</legend>
                <table border="0" cellpadding="5">
                    <tr>
                        <td>Fecha:</td>
                        <td>
                            <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha_dmy; ?>"/>
                            <span style="background-color: #ff8; cursor: pointer; padding: 2px 5px; border: 1px solid #ccc;"
                                  onmouseover="this.style.backgroundColor='#ff0';"
                                  onmouseout="this.style.backgroundColor='#ff8';"
                                  id="show_fecha" 
                            ><?php echo $fecha_dmy; ?></span>
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField     :    "fecha",
                                    ifFormat       :    "%d/%m/%Y",
                                    displayArea    :    "show_fecha",
                                    daFormat       :    "%d/%m/%Y",
                                    align          :    "Tl",
                                    singleClick    :    true,
                                    weekNumbers    :    false
                                });
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <td>Monto Bs/USD:</td>
                        <td><input type="text" name="montobs" id="montobs" value="<?php echo $row['montobs']; ?>" size="15" maxlength="15" /></td>
                    </tr>
                </table>
                <br />
                <input type="submit" value="Confirmar Cambios" />
                <input type="button" value="Cancelar" onclick="window.location='tasaBCV.php'" />
            </fieldset>
        </form>
    <?php } ?>

</div>
</body>
</html>
