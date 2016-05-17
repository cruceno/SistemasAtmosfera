$(document).ready($(function () {
  function mvFormatter(v, axis) {
    return v.toFixed(axis.tickDecimals) + ' mV';
  }
  function doPlot(data) {
    //-------------------------------------------------------------------------------
    //------------------------// Opciones del grafico //-----------------------------
    var opciones = {
      series: {
        lines: {
          show: true,
          lineWidth: 0.8
        },
        points: {
          show: false,
          fill: false
        }
      },
      xaxes: [
        {
          mode: 'time',
          minTickSize: [
            2,
            'hour'
          ],
          //ticks: data[0].length/(12*60),
          //timeformat: '%H:%M:%S'
        }
      ],
      yaxes: [
        {
          min: -2,
          tickFormatter: mvFormatter
        },
        {
          alignTicksWithAxis: 0, // align if we are to the right
          position: 'rigth',
          tickFormatter: mvFormatter
        }
      ],
      legend: {
        position: 'ne',
        noColumns: 2
      },
      grid: {
        hoverable: true
      }, //IMPORTANT! this is needed for tooltip to work
      tooltip: true,
      tooltipOpts: {
        content: '%s para %x: %y',
        xDateFormat: '%Y-%m-%d %H:%M:%S',
        onHover: function (flotItem, $tooltipEl) {
          // console.log(flotItem, $tooltipEl);
        }
      }
    }
    //-----------------------// Opciones del grafico //-------------------------------
    //--------------------------------------------------------------------------------
    //Ejecutar grafica ! 
    $.plot($('#flot-line-chart-multi'), // Div donde se va a colocar el grafico. 
    data, // Se ingresan los arrays con los datos y labels para cada canal.
    opciones
    // Se ingresan las opciones para el grafico
    );
  }
  $.ajax({
    url: './includes/db_rutines.php',
    data: {
      action: 'getDatatoPlot'
    },
    type: 'GET',
    dataType: 'json',
    success: doPlot
  });
  
  })
	    	
);
