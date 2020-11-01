$(document).on('ready',function(){

	/* ********************************************************************************************************************************
										MANTENIMIENTO PRODUCTOS
	******************************************************************************************************************************** */

	
	function tablaStock(){
		var codLocal = $('#codLocal').val();

		$('#tablaStock').dataTable().fnDestroy();		 	
		$('#tablaStock').DataTable({

			//PARA EXPORTAR
			
			dom: "Bfrtip",
			buttons: [{
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,
			
			//"order" : [ [ 0, "desc" ] ],
			"ajax" : "stock/getStock/"+codLocal,
			"columns" : [
			{
				"data" : "nIDPRODUCTO"
			},{
				"data" : "PRODUCTO"
			},{
				"data" : "nCANTIDAD"
			},	
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablaStock();
});

