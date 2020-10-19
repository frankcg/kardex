$(document).on('ready',function(){

	optionsToastr = {	closeButton: true,
						progressBar: true,
						preventDuplicates: true,
						newestOnTop: true,};

	$('#btnBuscar').click(function(){

		var codCompra = $('#codCompra').val();
		var fechaInicio = $('#fechaInicio').val();
		var fechaFin = $('#fechaFin').val();

		if(codCompra=='' && fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Anulacion', {optionsToastr});} 
		else if(codCompra=='' && ((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Anulacion', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Anulacion', {optionsToastr});}
		else{			
			tablaCompras(((codCompra=='') ? 'vacio' : codCompra), fechaInicio, fechaFin);
		}
	});


	function tablaCompras(codCompra=0, fechaInicio='', fechaFin=''){
		$('#tablaCompra').dataTable().fnDestroy();		 	
		$('#tablaCompra').DataTable({

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
			
			//"order" : [ [ 0, "desc" ] ],
			"ajax" : "anulacion/getCompras/"+codCompra+'/'+fechaInicio+'/'+fechaFin,
			"columns" : [
			{
				"data" : "FECHA_COMPRA"
			},{
				"data" : "IDCOMPRA"
			},{
				"data" : "PROVEEDOR"
			},{
				"data" : "CANTIDAD_TOTAL_COMPRA"
			},{
				"data" : "COSTO_TOTAL_COMPRA"
			},		
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}



});


	