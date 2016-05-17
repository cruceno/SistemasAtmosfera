<?php
include_once 'conn.php';
// date_default_timezone_set('America/Buenos_Aires');
function get_data($d1, $d2, $sort) {
	// strtotime convierte cadena de fecha en tipo time
	// date("Y-m-d H:i:s"); # Formato de fecha DATETIME MySQL
	$link = conn_db ();
	$sort = is_null ( $sort ) ? 'DESC' : $sort;
	$d1 = is_null ( $d1 ) ? date ( "Y-m-d H:i:s" ) : date ( "Y-m-d H:i:s", strtotime ( $d1 ) );
	$d2 = is_null ( $d2 ) ? date ( "Y-m-d H:i:s", mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - 1, date ( "Y" ) ) ) : date ( "Y-m-d H:i:s", strtotime ( $d2 ) );
	// echo $d1,$d2;
	$query = "SELECT * FROM data WHERE datetime BETWEEN '" . $d2 . "'  AND '" . $d1 . "' ORDER BY datetime " . $sort . ";";
	$result = mysql_query ( $query );
	
	if (! $result) {
		die ( 'Consulta no v�lida: ' . mysql_error () );
	}
	
	$rows = array ();
	
	for($i = 0; $i <= mysql_num_rows ( $result ); $i ++) :
		$rows [] = mysql_fetch_row ( $result );
	endfor
	;
	
	mysql_close ( $link );
	
	return $rows;
}
function get_single_channel_data($channel, $d1, $d2) {
	$link = conn_db ();
	$d1 = is_null ( $d1 ) ? date ( "Y-m-d H:i:s" ) : date ( "Y-m-d H:i:s", strtotime ( $d1 ) );
	$d2 = is_null ( $d2 ) ? date ( "Y-m-d H:i:s", mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - 1, date ( "Y" ) ) ) : date ( "Y-m-d H:i:s", strtotime ( $d2 ) );
	
	$query = "SELECT `datetime`, `" . $channel . "` FROM data WHERE datetime BETWEEN '" . $d2 . "'  AND '" . $d1 . "' ORDER BY datetime ASC;";
	$result = mysql_query ( $query );
	
	$data = array ();
	$UTC = new DateTimeZone ( "UTC" );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		
		$date = new DateTime ( $row ['datetime'], $UTC );
		$data [] = array (
				$date->getTimestamp () * 1000,
				intval ( $row [$channel] ) 
		);
	}
	
	return $data;
}
function get_channels_config($channels_to_get) {
	$link = conn_db ();
	$query = "SELECT * FROM channels";
	$result = mysql_query ( $query );
	
	$channels = array ();
	if (is_null ( $channels_to_get )) {
		for($i = 0; $i < mysql_num_rows ( $result ); $i ++) :
			$channels [] = mysql_fetch_object ( $result );
		endfor
		;
	} 

	else {
		for($i = 0; $i < mysql_num_rows ( $result ); $i ++) :
			$chan = mysql_fetch_object ( $result );
			if (in_array ( $chan->channel, $channels_to_get )) {
				$channels [] = $chan;
			}
		endfor
		;
	}
	
	mysql_close ( $link );
	
	return $channels;
}
function getDatatoPlot() {
	$channels = get_channels_config ( Null );
	
	foreach ( $channels as $channel ) {
		// echo $channel->model;
		if ($channel->status == 1) {
			if ($channel->channel == 'C1' || $channel->channel == 'C2' || $channel->channel == 'C3' || $channel->channel == 'C6' || $channel->channel == 'C7') {
				$arr [] = array (
						'label' => $channel->model,
						'data' => get_single_channel_data ( $channel->channel, Null, Null ) 
				);
			} else {
				$arr [] = array (
						'label' => $channel->model,
						'data' => get_single_channel_data ( $channel->channel, Null, Null ),
						'yaxis' => 2 
				);
			}
		}
	}
	
	echo json_encode ( $arr );
}
function getDatatoPlotMulti($channels_to_plot, $d1, $d2) {
	if ($channels_to_plot) {
		
		$channels_to_get = $channels_to_plot;
		
		$channels = get_channels_config ( $channels_to_get );
		$yaxis = False;
		foreach ( $channels as $channel ) {
			
			if ($channel->channel == 'C1' || $channel->channel == 'C2' || $channel->channel == 'C3' || $channel->channel == 'C6' || $channel->channel == 'C7') {
				$arr [] = array (
						'label' => $channel->model,
						'data' => get_single_channel_data ( $channel->channel, $d1, $d2 ) 
				);
				$yaxis = True;
			} elseif ($yaxis == False && $channel->channel == 'C4') {
				$arr [] = array (
						'label' => $channel->model,
						'data' => get_single_channel_data ( $channel->channel, $d1, $d2 ) 
				);
			} else {
				$arr [] = array (
						'label' => $channel->model,
						'data' => get_single_channel_data ( $channel->channel, $d1, $d2 ),
						'yaxis' => 2 
				);
			}
		}
		echo json_encode ( $arr );
	}
}
function sendDataToFile($d1, $d2) {
	$entries = get_data ( $d1, $d2, 'ASC' );
	if ($entries) {
		// abrir un archivo, en este caso un archivo temporal de hasta 20MB
		// (si es m�s grande, lo escribe a un archivo)
		$channels = get_channels_config ( Null );
		$header = [ 
				array (),
				array () 
		];
		foreach ( $channels as $channel ) {
			$header [0] [] = $channel->channel;
			$header [1] [] = $channel->model;
		}
		
		$fp = fopen ( 'php://temp/maxmemory:' . (20 * 1024 * 1024), 'r+' );
		
		foreach ( $header as $line ) {
			if (! empty ( $row )) {
				fputcsv ( $fp, $line, chr ( 9 ) );
			}
		}
		
		foreach ( $entries as $row ) {
			if (! empty ( $row )) {
				fputcsv ( $fp, $row, chr ( 9 ) );
			}
		}
		// be kind, rewind (devolver la posici�n del puntero del archivo)
		rewind ( $fp );
		// obtener contenido del archivo como un string
		$output = stream_get_contents ( $fp );
		// cerrar archivo
		fclose ( $fp );
		// cabeceras HTTP:
		// tipo de archivo y codificaci�n
		header ( 'Content-Type: text/csv; charset=utf-8' );
		// forzar descarga del archivo con un nombre de archivo determinado
		header ( 'Content-Disposition: attachment; filename=archivo-' . time () . '.txt' );
		// indicar tama�o del archivo
		header ( 'Content-Length: ' . strlen ( $output ) );
		// enviar archivo
		echo $output;
		exit ();
	} else {
		echo 'No';
	}
}

if (isset ( $_GET ['action'] ) && ! empty ( $_GET ['action'] )) {
	$action = $_GET ['action'];
	switch ($action) {
		case 'getDatatoPlot' :
			getDatatoPlot ();
			break;
		case 'sendDataToFile' :
			sendDataToFile ( $_GET ['d2'], $_GET ['d1'] );
			break;
		case 'getDatatoPlotMulti' :
			getDatatoPlotMulti ( $_GET ['channels_to_plot'], $_GET ['d2'], $_GET ['d1'] );
			break;
		// ...etc...
	}
}

?>


