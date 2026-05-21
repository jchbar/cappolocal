<?php

//Copyright (C) 2000-2006  Antonio Grandío Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adrián http://www.inmaecharri.com

//This file is part of Catwin.

//CatWin is free software; you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation; either version 2 of the License, or
//(at your option) any later version.

//CatWin is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details:
//http://www.gnu.org/copyleft/gpl.html

//You should have received a copy of the GNU General Public License
//along with Catwin Net; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

if (!is_dir("_backups")) {mkdir("_backups");}

deldir("_backups");

$fich = $_SESSION['empresa']."---".date('Y-m-d-His').".sql";

if( !@function_exists('gzopen')) {
	$gz = false;
	$f = fopen( "_backups/".$fich, 'w' );
}
else {
	$fich = $fich . ".gz";
	$gz = true;
	$f = gzopen( "_backups/".$fich, 'w6' );
}

$db = mysql_select_db($_SESSION['empresa']);

$escribir = "-- Base de datos: ".$_SESSION['empresa']." - Fecha: " . strftime( "%d/%m/%Y - %H:%M:%S", time() )."\n";
escribir($f, $gz, $escribir);

$result = mysql_query( 'SHOW tables' );
while( $fila = mysql_fetch_array($result) ) {
	if ($_SESSION['empresa'] == "nuevocat" AND $fila[0] == "referers") {continue;}
	escribetabla( $fila[0], $f, $gz);
	escribir($f, $gz, "\n");
}

if( !$gz ){ 
	fclose($f);
	} else {
	gzclose($f);
}

echo "<a href='_backups/$fich'>Bajar la copia de seguridad <span class='b'>$fich</span></a>";

function escribetabla($table, $f = 0, $gz) {
	$escribir = "\n-- Tabla `$table`\n";
	escribir($f, $gz, "\n");
	escribir($f, $gz, $escribir);
	$escribir = mysql_fetch_array(mysql_query("SHOW CREATE TABLE $table"));
	quitar($escribir['Create Table']);
	$escribir = "DROP TABLE IF EXISTS $table;\n" . $escribir['Create Table'] . ";\n\n";
	escribir($f, $gz, $escribir);
	$escribir = "--\n";
	escribir($f, $gz, $escribir);
	$escribir = "-- Dumping `$table`\n";
	escribir($f, $gz, $escribir);
	$escribir = "--\n\n";
	escribir($f, $gz, $escribir);
	$escribir = "LOCK TABLES $table WRITE;\n";
	escribir($f, $gz, $escribir);

	$result = mysql_query("SELECT * FROM $table");
	$campos=mysql_num_fields($result);
	while ($fila = mysql_fetch_array($result)) {
		$escribir = "INSERT INTO $table VALUES(";
		escribir($f, $gz, $escribir);
		$n = 0;
		while ($n < $campos) {
			$escribir = "";
			if ($n) {$escribir = ", ";}
			if( !isset($fila["$n"])) {$escribir .= 'NULL';} else {$escribir .= "'" . mysql_escape_string($fila["$n"]) . "'";}
			escribir($f, $gz, $escribir);
			$n = $n+1;
		}
		$escribir = ");\n";
		escribir($f, $gz, $escribir);
	}
	$escribir = "UNLOCK TABLES;";
	escribir($f, $gz, $escribir);
}

function quitar(&$text) {
	return $text;
}

function escribir($f, $gz, $escribir) {
	if( !$gz ){
		fwrite( $f, $escribir );
	} else {
		gzwrite( $f, $escribir );	
	}
}

function deldir($dir){
	$current_dir = opendir($dir);
	while($entryname = readdir($current_dir)){
		if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
			 deldir("${dir}/${entryname}");
			}elseif($entryname != "." and $entryname!=".."){
			unlink("${dir}/${entryname}");
		}
	}
	closedir($current_dir);
}

?>