$(document).on('ready',function(){

	$( "#city" ).autocomplete({
	 	source: function( request, response ) {
	   	
	  	$.ajax({
			url: "reporte/prueba",
	    	type: 'post',
	    	dataType: "json",
	    	data: {
	     		search: request.term
	    	},
	    	success: function( data ) {
	    		response( data );
			}
		});
		},select: function (event, ui) {		
			$('#city').val(ui.item.label); // display the selected text
	  		//$('#selectuser_id').val(ui.item.value); // save selected id to input
	  		return false;
	  	}
	});


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
				"data" : "IDVENTA"
			},{
				"data" : "IDCOMPRA"
			},{
				"data" : "IDPRODUCTO"
			},{
				"data" : "FECHA_VENTA"
			},{
				"data" : "PRODUCTO"
			},{
				"data" : "CANTIDAD"
			},{
				"data" : "PRECIO_COMPRA_UNIDAD"
			},{
				"data" : "PRECIO_VENTA_UNIDAD"
			},{
				"data" : "PRECIO_VENTA_TOTAL"
			},{
				"data" : "GANANCIA"
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

