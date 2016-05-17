$(document).ready($(function () {
  $('#plotForm').submit(function (e) {
    e.preventDefault();
    $.ajax({
      url: './includes/db_rutines.php',
      data: $(this).serialize(),
      type: 'GET',
      dataType: 'json',
      success: doPlot
    });
  });
  function doPlot(data) {
    function mvFormatter(v, axis) {
      return v.toFixed(axis.tickDecimals) + ' mV';
    }
    //console.log(JSON.stringify (data));

    function getData(x1, x2) {
      x1 = Math.ceil(x1);
      x2 = Math.ceil(x2);
      var newData = [
      ];
      for (d in data) {
        //console.log(JSON.stringify(data[d].data));
        var pos1 = false;
        var pos2 = false;
        var l1 = 0;
        var l2 = 0;
        for (pos in data[d].data) {
          if (pos != 0) {
            l1 = Number(pos) - 1
          } else {
            l1 = Number(pos)
          }
          if (pos != data[d].data.length - 1) {
            l2 = Number(pos) + 1
          } else {
            l2 = Number(pos)
          }
          //console.log(l1,l2);

          if (!pos1 && Number(data[d].data[l1][0]) <= x1 && x1 <= Number(data[d].data[l2][0])) {
            var posx1 = Number(pos);
            pos1 = true;
            //console.log('Index :',posx1,' \nLimite l1 ',Number(data[d].data[l1][0]),' \nx1: ',x1, '\nLimite l2: ', Number(data[d].data[l2][0]));
          }
          if (!pos2 && Number(data[d].data[l1][0]) <= x2 && x2 <= Number(data[d].data[l2][0])) {
            var posx2 = Number(pos);
            pos2 = true;
            //console.log(posx2);
            //console.log('Index :',posx2,' \nLimite l1 ',Number(data[d].data[l1][0]),' \nx2: ',x2, '\nLimite l2: ', Number(data[d].data[l2][0]));
          }
        }
        if (data[d].yaxis) {
          var channel = {
            'label': data[d].label,
            'data': data[d].data.slice(posx1, posx2),
            'yaxis': data[d].yaxis
          }
        } else {
          var channel = {
            'label': data[d].label,
            'data': data[d].data.slice(posx1, posx2)
          }
        }
        newData.push(channel);
      }
      //console.log(JSON.stringify(newData));

      return newData;
    }
    //-------------------------------------------------------------------------------
    //------------------------// Opciones del grafico //-----------------------------

    var opciones = {
      series: {
        lines: {
          show: true,
          //lineWidth: 1
        },
        points: {
          show: false,
          fill: false
        },
        //shadowSize: 0.8
      },
      xaxes: [
        {
          mode: 'time',
          //ticks: data[0].length/(12*60),
          //timeformat: '%H:%M:%S'
        }
      ],
      yaxes: [
        {
          min: -5,
          tickFormatter: mvFormatter,
          position:'left'
        },
        {
          min:-5,
          alignTicksWithAxis: 0, // align if we are to the right
          position: 'rigth',
          tickFormatter: mvFormatter
        }
      ],
      legend: {
        position: 'sw',
        noColumns: 2
      },
      selection: {
        mode: 'x'
      },
      grid: {
        hoverable: true
      }, //IMPORTANT! this is needed for tooltip to work
      tooltip: true,
      tooltipOpts: {
        content: '%s para %x was %y',
        xDateFormat: '%Y-%m-%d %H:%M:%S',
        onHover: function (flotItem, $tooltipEl) {
          // console.log(flotItem, $tooltipEl);
        }
      }
    }
    //-----------------------// Opciones del grafico //-------------------------------
    //--------------------------------------------------------------------------------
    //Ejecutar grafica ! 

    var plot = $.plot($('#flot-chart'), // Div donde se va a colocar el grafico. 
    data, // Se ingresan los arrays con los datos y labels para cada canal.
    opciones
    // Se ingresan las opciones para el grafico
    );
    var overview = $.plot($('#overview'), data, {
      legend: {
        show: false
      },
      series: {
        lines: {
          show: true,
          lineWidth: 1
        },
        shadowSize: 0
      },
      xaxes: [
        {
          mode: 'time'
          //ticks: data[0].length/(12*60),
        }
      ],
      yaxes: [
        {
          min: -5,
          tickFormatter: mvFormatter
        },
        { min:-5,
          alignTicksWithAxis: 0, // align if we are to the right
          position: 'rigth',
          tickFormatter: mvFormatter
        }
      ],
      grid: {
        color: '#999'
      },
      selection: {
        mode: 'x'
      }
    });
    $('#flot-chart').bind('plotselected', function (event, ranges) {
      // clamp the zooming to prevent eternal zoom
      if (ranges.xaxis.to - ranges.xaxis.from < 0.00001) {
        ranges.xaxis.to = ranges.xaxis.from + 0.00001;
      }
      if (ranges.yaxis.to - ranges.yaxis.from < 0.00001) {
        ranges.yaxis.to = ranges.yaxis.from + 0.00001;
      }
      // do the zooming			

      plot = $.plot('#flot-chart', getData(ranges.xaxis.from, ranges.xaxis.to), $.extend(true, {
      }, opciones, {
        xaxis: {
          min: ranges.xaxis.from,
          max: ranges.xaxis.to
        },
        yaxis: [{min: ranges.yaxis.from,
                 max: ranges.yaxis.to}, 
                {min: ranges.yaxis.from,
                 max: ranges.yaxis.to
                 }
          ]
      })
      );
      // don't fire event on the overview to prevent eternal loop
      overview.setSelection(ranges, true);
    });
    $('#overview').bind('plotselected', function (event, ranges) {
      plot.setSelection(ranges);
    });
    // Add the Flot version string to the footer
    //$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
  }
})
);
