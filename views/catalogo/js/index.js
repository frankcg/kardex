$(document).on('ready',function(){

	/* ********************************************************************************************************************************
										MANTENIMIENTO PRODUCTOS
	******************************************************************************************************************************** */

	
	function tablaCatalogo(){
		$('#tablaCatalogo').dataTable().fnDestroy();		 	
		$('#tablaCatalogo').DataTable({

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
			"ajax" : "catalogo/getCatalogo",
			"columns" : [
			{
				"data" : "CONT"
			},{
				"data" : "IDSTOCK"
			},{
				"data" : "IDPRODUCTODETALLE"
			},{
				"data" : "PRODUCTO"
			},{
				"data" : "MARCA"
			},{
				"data" : "MODELO"
			},{
				"data" : "PRECIO"
			},{
				"data" : "STOCK_GENERAL"
			},{
				"data" : "CANTIDAD_VENTAS"
			},{
				"data" : "STOCK_ACTUAL"
			},		
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablaCatalogo();
});

