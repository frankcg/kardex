$(document).on('ready',function(){

	$('#evaluacion').change(function(){
		var idevaluacion = $('#evaluacion').val();
		$.post('busqueda/getevaluadores',{
			idevaluacion : idevaluacion
		},function(data){
			//alert(data);
			$('#evaluador').html(data);
			$('#evaluado').html('<option selected="selected" disabled="disabled">--SELECCIONE--</option> ');
		});
	});

	$('#evaluador').change(function(){
		var idevaluacion = $('#evaluacion').val();
		var idevaluador = $('#evaluador').val();
		$.post('busqueda/getevaluados',{
			idevaluacion : idevaluacion,
			idevaluador : idevaluador
		},function(data){
			//alert(data);
			$('#evaluado').html(data);
		});
	});


	function tablaevaluaciones(idevaluacion, idevaluador, idevaluado){
		$('#tablaevaluaciones').dataTable().fnDestroy();		 	
		$('#tablaevaluaciones').DataTable({

			//PARA EXPORTAR
			/*
			dom: "Bfrtip",
			buttons: [{
				extend: "copy",
				className: "btn-sm"
			}, {
				extend: "csv",
				className: "btn-sm"
			}, {
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,*/
			
			"order" : [ [ 0, "desc" ] ],
			"ajax" : "busqueda/getevaluaciones/"+idevaluacion+"/"+idevaluador+"/"+idevaluado,
			"columns" : [
			{
				"data" : "NOMBRE_EVALUACION"
			},{
				"data" : "IDEVALUADO"
			},{
				"data" : "IDEVALUADOR"
			},{
				"data" : "NOMBRE_COMPETENCIA"
			},{
				"data" : "NOMBRE_CONDUCTA"
			},	
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$('#btn_consultar').click(function(){

		var idevaluacion = $('#evaluacion').val();
		var idevaluador = $('#evaluador').val();
		var idevaluado = $('#evaluado').val();

		if(idevaluacion != null && idevaluador != null && idevaluado != null){

			tablaevaluaciones(idevaluacion, idevaluador, idevaluado);

		}

	});

	/* *******************************************************************************
						BUSQUEDA X AREA
	******************************************************************************* */

	$('#evaluacion2').change(function(){
		var idevaluacion = $('#evaluacion2').val();
		$.post('busqueda/getareas',{
			idevaluacion : idevaluacion
		},function(data){
			//alert(data);
			$('#area').html(data);
		});
	});


	function tablaevaluacionesxarea(idevaluacion, idarea){
		$('#tablaevaluacionesxarea').dataTable().fnDestroy();		 	
		$('#tablaevaluacionesxarea').DataTable({

			//PARA EXPORTAR
			/*
			dom: "Bfrtip",
			buttons: [{
				extend: "copy",
				className: "btn-sm"
			}, {
				extend: "csv",
				className: "btn-sm"
			}, {
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,*/
			
			"order" : [ [ 0, "desc" ] ],
			"ajax" : "busqueda/getevaluacionesxarea/"+idevaluacion+"/"+idarea,
			"columns" : [
			{
				"data" : "NOMBRE_EVALUACION"
			},{
				"data" : "IDEVALUADO"
			},{
				"data" : "PROMEDIO"
			},
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$('#btn_consultar_area').click(function(){

		var idevaluacion = $('#evaluacion2').val();
		var idarea = $('#area').val();	

		if(idevaluacion != null && idarea != null){
			tablaevaluacionesxarea(idevaluacion, idarea);
		}

	});

});