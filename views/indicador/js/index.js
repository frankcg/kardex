$(document).on('ready',function(){

	$('#tab-picture').hide();

	function tablaindicador_cumplimiento(idevaluacion){
		$('#tablaindicador_cumplimiento').dataTable().fnDestroy();		 	
		$('#tablaindicador_cumplimiento').DataTable({

			//PARA EXPORTAR
			
			dom: "Bfrtip",
			buttons: [{
				extend: "excel",
				className: "btn-xs"
			},{
				extend: "pdf",
				className: "btn-xs"
			}],
			responsive: !0,
			
			"order" : [ [ 1, "asc" ] ],
			"ajax" : "indicador/getcumplimiento/"+idevaluacion+"/"+1,
			"columns" : [
			{
				"data" : "FECHA"
			},{
				"data" : "AREA"
			},{
				"data" : "CANT_PREVISTA"
			},{
				"data" : "CANT_REALIZADA"
			},{
				"data" : "CANT_VERAZ"
			},{
				"data" : "CANT_INCONCLUSAS"
			},{
				"data" : "INDICADOR"
			},	
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	function tablaindicador_eficacia(idevaluacion){
		$('#tablaindicador_eficacia').dataTable().fnDestroy();		 	
		$('#tablaindicador_eficacia').DataTable({

			//PARA EXPORTAR
			
			dom: "Bfrtip",
			buttons: [{
				extend: "excel",
				className: "btn-xs"
			},{
				extend: "pdf",
				className: "btn-xs"
			}],
			responsive: !0,
			
			"order" : [ [ 1, "asc" ] ],
			"ajax" : "indicador/getcumplimiento/"+idevaluacion+"/"+2,
			"columns" : [
			{
				"data" : "FECHA"
			},{
				"data" : "AREA"
			},{
				"data" : "CANT_PREVISTA"
			},{
				"data" : "CANT_REALIZADA"
			},{
				"data" : "CANT_VERAZ"
			},{
				"data" : "CANT_INCONCLUSAS"
			},{
				"data" : "INDICADOR"
			},	
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$('#btn_consultar').click(function(){
		var idevaluacion = $('#evaluacion').val();
		var name_idevaluacion = $('#evaluacion option:selected').html();
		$('.id-name-evaluation').html(name_idevaluacion);
		//console.log(name_idevaluacion);
		if(idevaluacion != null){
			tablaindicador_cumplimiento(idevaluacion);
			tablaindicador_eficacia(idevaluacion);
			$('#tab-picture').show();

			$.ajax({
			   type: "POST",
			   dataType: "json",
			   data: {idevaluacion:idevaluacion, indice:1},
			   url: "indicador/getdashboard",
			   success: function(msg){
			   		get_dashboard_eficacia(msg);
	 				get_dashboard_grado_cumplimiento(msg);			     
			   }
			});
		}
	});

	/******************************************************************************
	 								DASHBOARD
	 ****************************************************************************** */

	 function get_dashboard_eficacia(msg){

	 	$('#porcentaje-eficacia').html(msg.INDICADOR_EFI);
	 	var chartColor = pxDemo.getRandomColors(1)[0];
	      var data = {
	        labels: ['N° Previsto', 'N° Realizadas', 'N° Veraces', 'N° No Realizadas' ],
	        datasets: [{
	          label: 'Cantidad',
	          //data:  [ '"'+msg.CANT_PREVISTA+'","'+msg.CANT_REALIZADA+'","'+msg.CANT_VERAZ+'","'+msg.CANT_NO_REALIZADA+'"'],
	          data:  [ msg.CANT_PREVISTA,msg.CANT_REALIZADA,msg.CANT_VERAZ,msg.CANT_NO_REALIZADA],
	          borderWidth:     1,
	          backgroundColor: pxUtil.hexToRgba(chartColor, 0.3),
	          borderColor:     chartColor,
	        }],
	      };

	      //console.log(data);

	      new Chart(document.getElementById('efcacia-chart').getContext("2d"), {
	        type: 'bar',
	        data: data,
	        options: {
	          legend: { display: false },
	        },
	      });	 	
	 }



	 function get_dashboard_grado_cumplimiento(msg){

	 	$('#porcentaje-grado-cp').html(msg.INDICADOR_GC);

	 	  var chartColor = pxDemo.getRandomColors(1)[0];
	      var data = {
	        labels: ['N° Previsto', 'N° Realizadas', 'N° Veraces', 'N° Inconclusas' ],
	        datasets: [{
	          label: 'Cantidad',
	          data:  [ msg.CANT_PREVISTA,msg.CANT_REALIZADA,msg.CANT_VERAZ,msg.CANT_INCONCLUSAS],
	          borderWidth:     1,
	          backgroundColor: pxUtil.hexToRgba(chartColor, 0.3),
	          borderColor:     chartColor,
	        }],
	      };

	      //console.log(data);

	      new Chart(document.getElementById('grado-cumplimiento-chart').getContext("2d"), {
	        type: 'bar',
	        data: data,
	        options: {
	          legend: { display: false },
	        },
	      });
	 }

	 

});