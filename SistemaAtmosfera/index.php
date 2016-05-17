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
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Sistema de monitoreo</a>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <div id=sidebar class="col-sm-3 col-md-2 sidebar collapse">
          <ul id="tabs" class="nav nav-pills nav-stacked" role="tablist" data-tabs="tabs">
            <li class="active" ><a href="#resumen" data-toggle="tab" role="tab">Resumen</a></li>
            <li><a href="./analisis.php" >An&aacute;lisis</a></li>
            <li><a href="#exportar" data-toggle="tab" role="tab">Exportar</a></li>
          </ul>
      </div>
        <div class="tab-content col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
         <div id=resumen role="tabpanel" class="tab-pane fade in active">
          <h1 class="page-header">Resumen</h1>
          <div class="row placeholders">
          		<div id="flot-line-chart-multi" class="flot-chart-content"></div>
          </div>
		  
          <h2 class="sub-header">Ultimos datos</h2>
          <div class="table-responsive">
            <table class="table table-striped">
     	      <?php $channels=get_channels_config(Null); ?>
       	      <thead>
       	        <tr>
       	          <th>Fecha <br><small>(YYYY-MM-DD HH:mm:ss)</small></th>
      	     		<?php
            		  foreach ($channels as $channel):?>
            			<th><?php echo 'Canal ',$channel->channel,'<br><small>',$channel->model,'</small>';?></th>
            		<?php endforeach;?>
				</tr>
              </thead>
              <tbody>
                <?php 
                $rows=get_data(Null,Null,Null);
                foreach($rows as $row):?>
                <tr>
                  <?php for ($i=0;$i<=8;$i++):?>
                  	
              		<td><?php echo $row[$i]; //Remplazar el valor 9999 por la etiqueta de deshabilitado?></td>
              	  <?php endfor;?>
              	</tr>
              	  <?php endforeach;?>	
              </tbody>
            </table>
          </div>
        </div>
      	
      	<div id=analisis role="tabpanel" class="tab-pane fade">
      	</div>
      	
      	<div id=exportar role="tabanel" class="tab-pane fade">
      		<h2>Expotar archivo de datos</h2>
			<form class="form-horizontal" name="export" id="export">
			  <div class="form-group">
			    <label class="col-sm-3 control-label" for="d1">Ingrese limite inferior</label>
			    <div class="col-sm-4">
			    	<input type="date" class="form-control" name="d1" min="2016-02-03"  pattern="(?:201)[0-9]{1}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" placeholder="YYYY-MM-DD">
			    	<p class="help-block">En el caso de Internet Explorer y Firefox el formato debe ser YYYY-MM-DD, respetando cantidad de digitos y guiones medios. La fecha no puede ser inferior al 2016-02-03</p>
			 	</div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-3 control-label" for="d2">Ingrese limite superior</label>
			    <div class="col-sm-4">
			    	<input type="date" class="form-control" min="2016-02-04" name="d2" pattern="(?:201)[0-9]{1}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" placeholder="YYYY-MM-DD">
			    	<p class="help-block">En el caso de Internet Explorer y Firefox el formato debe ser YYYY-MM-DD, respetando cantidad de digitos y guiones medios. La fecha no puede ser inferior al 2016-02-03</p>
				</div>			  
			  </div>
			  <input type="hidden" name="action" value="sendDataToFile">
			  <div class="col-sm-offset-3 col-sm-4">
			  	<button type="submit" class="btn-lg btn-primary">Exportar datos !</button>
			  </div>
			</form>
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
    <script src="./js/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>

    <!-- Atmosfera JavaScript -->
    <script src="./js/atmosfera.js"></script>
    <script src="./js/atmosfera.flot.js"></script>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
