<?php
include './includes/db_rutines.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../../favicon.ico">

<title>Sistema de monitoreo</title>

<!-- Bootstrap core CSS -->
<link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="./assets/css/ie10-viewport-bug-workaround.css"
	rel="stylesheet">

<!-- Custom styles for this template -->
<link href="atmosfera.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]>
		<script src="../../assets/js/ie8-responsive-file-warning.js"></script>
	<![endif]-->
<script src="./assets/js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#sidebar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Sistema de monitoreo</a>
			</div>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div id=sidebar class="col-sm-3 col-md-2 sidebar collapse">
				<ul id="tabs" class="nav nav-pills nav-stacked" role="tablist">
					<li><a href="./index.php">Resumen</a></li>
					<li class="active"><a href="./analisis.php" role="tab">An&aacute;lisis</a></li>
					<li><a href="./index.php#exportar">Exportar</a></li>
				</ul>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<div class="col-sm-12 col-md-12">
					<form id="plotForm" class="form-horizontal">
						<input type="hidden" name="action" value="getDatatoPlotMulti">
						<div class="form-group col-sm-4 col-md-4">
							<label class="control-label" for="d1">Canales</label> <select
								required name="channels_to_plot[]" id="ChannelsToPlot" multiple
								class="form-control">
        	    <?php
													
$channels = get_channels_config ( Null );
													foreach ( $channels as $channel ) :
														if ($channel->status == 1) :
															echo '<option value="' . $channel->channel . '">', $channel->model, '</option>';
														
            	    endif;
													endforeach
													;
													?>
        	 	</select>
						</div>
						<div class="form-group col-sm-4 col-md-4">
							<label class="control-label" for="d1">Ingrese limite inferior</label>
							<input type="date" class="form-control" required id="d1"
								name="d1" min="2016-02-03"
								pattern="(?:201)[0-9]{1}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
								placeholder="YYYY-MM-DD"> <label class=" control-label" for="d2">Ingrese
								limite superior</label> <input type="date" class="form-control"
								required min="2016-02-04" id="d2" name="d2"
								pattern="(?:201)[0-9]{1}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
								placeholder="YYYY-MM-DD">
						</div>
						<div class="form-group col-sm-4 col-md-4">
							<button id="plot" type="submit"
								class="btn-lg btn-primary btn-block">Graficar</button>
						</div>
					</form>
				</div>
				<div id="flot-chart" style="height: 350px;"
					class="col-sm-9 col-md-9"></div>
				<div id="overview" style="height: 150px;" class="col-sm-3 col-md-3"></div>
				<div id="footer" class="col-sm-9 col-md-9"></div>
			</div>

		</div>
	</div>
	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="./js/jquery.min.js"></script>
	<script src="./bootstrap/js/bootstrap.min.js"></script>
	<script src="./bootstrap/js/bootstrap.js"></script>
	<!-- Flot Charts JavaScript -->
	<script src="./js/flot/excanvas.min.js"></script>
	<script src="./js/flot/jquery.flot.js"></script>
	<script src="./js/flot/jquery.flot.resize.js"></script>
	<script src="./js/flot/jquery.flot.time.js"></script>
	<script src="./js/flot/jquery.flot.selection.js"></script>
	<script src="./js/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>

	<!-- Atmosfera JavaScript -->
	<script src="./js/atmosfera.js"></script>
	<script src="./js/atmosfera.analisis.flot.js"></script>

	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
