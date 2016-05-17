$(document).ready($(function () {
  $('#export').submit(function (event){
	  $.ajax({
		    url: './includes/db_rutines.php',
		    data: $("#export").serialize(),
		    type: 'GET',
		    dataType: 'json',
	        });
  });
  
 })
 
);