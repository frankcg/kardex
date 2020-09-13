$(document).on('ready',function(){

	function tablasignacion(idarea, idevaluacion){
		$('#tablasignacion').dataTable().fnDestroy();		 	
		$('#tablasignacion').DataTable({

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
			"ajax" : "asignacion/getpersonal/"+idarea+"/"+idevaluacion,
			"columns" : [
			{
				"data" : "NOMBRE"
			},{
				"data" : "NOM_AREA"
			},{
				"data" : "NOM_CARGO"
			},{
				"data" : "GERENTE"
			},{
				"data" : "COLEGA"
			},{
				"data" : "CLIENTE"
			},{
				"data" : "PROVEEDOR"
			},	
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$('#btn_consultar').click(function(){

		var idevaluacion = $('#evaluacion').val();
		var idarea = $('#area').val();
		if(idevaluacion != null && idarea!=null){
			tablasignacion(idarea, idevaluacion);
		}
	});

	$("#tablasignacion tbody").on('change','select.colega',function(){
		
		var idcolega = $(this).val();
		var idevaluacion = $('#evaluacion').val();

		$.post('asignacion/addcolega',{
			idcolega : idcolega,
			idevaluacion : idevaluacion
		},function(data){
			if(data){
				toastr['success']('Se registro correctamente', 'Asignacion Colega', {
				    closeButton: true,
				    progressBar: true,
				    preventDuplicates: false,
					newestOnTop: true,
				});	
			}
		});
		
	});

	$("#tablasignacion tbody").on('change','select.cliente',function(){
		
		var idcliente = $(this).val();
		var idevaluacion = $('#evaluacion').val();

		$.post('asignacion/addcliente',{
			idcliente : idcliente,
			idevaluacion : idevaluacion
		},function(data){
			if(data){
				toastr['success']('Se registro correctamente', 'Asignacion Cliente', {
				    closeButton: true,
				    progressBar: true,
				    preventDuplicates: false,
					newestOnTop: true,
				});	
			}
		});
		
	});

	$("#tablasignacion tbody").on('change','select.proveedor',function(){
		
		var idproveedor = $(this).val();
		var idevaluacion = $('#evaluacion').val();

		$.post('asignacion/addproveedor',{
			idproveedor : idproveedor,
			idevaluacion : idevaluacion
		},function(data){
			if(data){
				toastr['success']('Se registro correctamente', 'Asignacion Proveedor', {
				    closeButton: true,
				    progressBar: true,
				    preventDuplicates: false,
					newestOnTop: true,
				});	
			}
		});
		
	});

});

