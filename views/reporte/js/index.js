$(document).on('ready',function(){

	$('#btnBuscar').click(function(){
		var fechaInicio = $('#fechaInicio').val();
		var fechaFin = $('#fechaFin').val();
		if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
			reporte_x_fecha(fechaInicio, fechaFin);			
		}
	});

	function reporte_x_fecha(fechaInicio, fechaFin){
		$('#tblReporteFecha').dataTable().fnDestroy();		 	
		$('#tblReporteFecha').DataTable({

			"footerCallback": function ( row, data, start, end, display ) {
	            var api = this.api(), data;
	 
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\$,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };
	 
	            // Total over all pages
	            total = api
	                .column( 9 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                }, 0 );
	            console.log(total);
	 
	            // Total over this page
	            pageTotal = api
	                .column( 9, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                }, 0 );
	 			console.log(pageTotal);

	 			$('#total').html(pageTotal);
	            // Update footer
	            /*$( api.column( 8 ).footer() ).html(
	                '$'+pageTotal +' ( $'+ total +' total)'
	            );*/
	        },

			//PARA EXPORTAR			
			dom: "Bfrtip",
			buttons: [{
				extend: "excel",
				className: "btn-sm"
			},],
			responsive: !0,
			
			//"order" : [ [ 0, "desc" ] ],
			"ajax" : "reporte/getReporteFecha/"+fechaInicio+"/"+fechaFin,
			"columns" : [
			{
				"data" : "CONT"
			},{
				"data" : "IDSTOCK"
			},{
				"data" : "IDPRODUCTODETALLE"
			},{
				"data" : "FECHA_VENTA"
			},{
				"data" : "PRODUCTO"
			},{
				"data" : "MARCA"
			},{
				"data" : "MODELO"
			},{
				"data" : "CANTIDAD"
			},{
				"data" : "PRECIO_SUGERIDO"
			},{
				"data" : "PRECIO_VENDIDO"
			},{
				"data" : "VENDEDOR",
				"visible": false
			},	
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			},
			

			



		});	
	}
	
	

});

