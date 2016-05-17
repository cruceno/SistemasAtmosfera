<?php
function conn_db() {
	$host = 'localhost';
	$user = 'atmosfera';
	$pass = 'lalalala';
	$base = 'atmosfera';
	$link = mysql_connect ( $host, $user, $pass );
	if (! $link) {
		die ( 'Consulta no v�lida: ' . mysql_error () );
	}
	$bd_seleccionada = mysql_select_db ( $base, $link );
	
	if (! $bd_seleccionada) {
		die ( 'No se puede usar foo : ' . mysql_error () );
	}
	return $link;
}
?>
