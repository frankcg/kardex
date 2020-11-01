$(document).on('ready',function(){

	function getEstados(datos){
		$.ajax({
			url: 'reporte/getEstados',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){
				var html='';
				$.each(data, function( i, v ) {
					html+= '<option value="'+v.nIDESTADO+'"> '+v.sDESCRIPCION+ '</option>'
				});
				console.log(data)
				$('#tipoVenta').html(html);
				$('#tipoCompra').html(html);
			}				
		});
	}

	getEstados();

	function tblReporteventas(codLocal=0, tipoVenta=0, fechaInicio='', fechaFin=''){
		$('#tblReporteventas').dataTable().fnDestroy();		 	
		$('#tblReporteventas').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getVentasTable/"+codLocal+'/'+tipoVenta+'/'+fechaInicio+'/'+fechaFin,
			"columns" : [	
				{ "data" : "dFECHAVENTA"},
				{ "data" : "nIDVENTA"},
				{ "data" : "sCLIENTE"},
				{ "data" : "sCostoTotalVenta"},
				{ "data" : "sPagoTotalVenta"},
				{ "data" : "sDeudaTotalVenta"},
				{ "data" : "OPCIONES"},
				
			]
		});	
	}
	
	function tblReportecompras(fechaInicio, fechaFin){
		$('#tblReportecompras').dataTable().fnDestroy();		 	
		$('#tblReportecompras').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getCompras/"+$('#tipoCompra').val()+"/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
			"columns" : [	
				{"data" : "nIDCOMPRA"},
				{"data" : "nIDLOCAL"},
				{"data" : "nLOCAL"},
				{"data" : "dFECHACOMPRA"},
				{"data" : "total"},
				{"data" : "OPCIONES", "visible": false},
			]
		});	
	}
	

	function tblReporteCuentasxPagar(fechaInicio, fechaFin){
		$('#tblReporteCuentasxPagar').dataTable().fnDestroy();		 	
		$('#tblReporteCuentasxPagar').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getCuentasPorPagar/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
			"columns" : [	
 				{"data" : "dFECHACOMPRA"}, 
				{"data" : "nIDCOMPRA"}, 
				{"data" : "nIDPROVEEDOR"}, 
				{"data" : "nIDLOCAL"}, 
				{"data" : "sOBSERVACION"}, 
				{"data" : "nCantidadTotalCompra"},
				{"data" : "sCostoTotalCompra"}, 
				{"data" : "sPagoTotalCompra"}, 
				{"data" : "sDeudaTotalCompra"},
			]
		});	
	}


	function tblReporteCuentasxCobrar(fechaInicio, fechaFin){
		$('#tblReporteCuentasxCobrar').dataTable().fnDestroy();		 	
		$('#tblReporteCuentasxCobrar').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getCuentasPorCobrar/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
			"columns" : [	
				{"data" : "dFECHAVENTA"},
				{"data" : "nIDVENTA"},
				{"data" : "nIDCLIENTE"},
				{"data" : "nIDLOCAL"},
				{"data" : "sOBSERVACION"},
				{"data" : "nCantidadTotalVenta"},
				{"data" : "sCostoTotalVenta"},
				{"data" : "sPagoTotalVenta"},
				{"data" : "sDeudaTotalVenta"},
				{"data" : "sCLIENTE"},
				{"data" : "sLOCAL"},

			]
		});	
	}

	function getInversion(){
		$('#tblReporteInversion').dataTable().fnDestroy();		 	
		$('#tblReporteInversion').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getInversion/"+$('#codLocal').val(),
			"columns" : [	
				{"data" : "nIDPRODUCTO"},
				{"data" : "sNOMBRE"},
				{"data" : "nCANTIDAD"},
				{"data" : "nTOTAL"},
			]
		});	
	}

	getInversion();


	function tblReporteBalance(fechaInicio, fechaFin){
		$('#tblReporteBalance').dataTable().fnDestroy();		 	
		$('#tblReporteBalance').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getReporteBalance/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
			"columns" : [	
 
				{"data" : "FECHA"},
				{"data" : "ventas"},
				{"data" : "ventasProductos"},
				{"data" : "ventasCosto"},
				{"data" : "efectivo"},
				{"data" : "deposito"},
				{"data" : "credito"},

			]
		});	
	}


	// $('#btnBuscar').click(function(){
	// 	var fechaInicio = $('#fechaInicio1').val();
	// 	var fechaFin = $('#fechaFin1').val();
	// 	if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
	// 		tblReporteventas(fechaInicio, fechaFin);			
	// 	}
	// });
	
	$('#btnBuscar').click(function(){

		var codLocal = $('#codLocal').val();
		var tipoVenta = $('#tipoVenta').val();
 		var fechaInicio = $('#fechaInicio1').val();
		var fechaFin = $('#fechaFin1').val();		

		if(fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Cuentas por Cobrar', {optionsToastr});} 
		else if(((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Cuentas por Cobrar', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Cuentas por Cobrar', {optionsToastr});}
		else{			
			tblReporteventas(codLocal,tipoVenta, fechaInicio, fechaFin);
		}
	});


	$('#btnBuscarCompras').click(function(){
		var fechaInicio = $('#fechaInicio2').val();
		var fechaFin = $('#fechaFin2').val();
		if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
			tblReportecompras(fechaInicio, fechaFin);			
		}
	});

	$('#btnBuscar5').click(function(){
		var fechaInicio = $('#fechaInicio5').val();
		var fechaFin = $('#fechaFin5').val();
		if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
			tblReporteCuentasxPagar(fechaInicio, fechaFin);			
		}
	});

	$('#btnBuscar6').click(function(){
		var fechaInicio = $('#fechaInicio6').val();
		var fechaFin = $('#fechaFin6').val();
		if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
			tblReporteCuentasxCobrar(fechaInicio, fechaFin);			
		}
	});

	$('#btnBuscar8').click(function(){
		var fechaInicio = $('#fechaInicio8').val();
		var fechaFin = $('#fechaFin8').val();
		if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
			tblReporteBalance(fechaInicio, fechaFin);			
		}
	});



	$("#tblReporteventas").on('click', '.viewPdf', function(){
		var myId = $(this).attr('id');
		datos = { 'id': myId};
		console.log(datos)
		$("#pdf_viewer").html('<iframe src = "venta/creacionFacturaurl/'+myId+'" width="100%"" height="600px" allowfullscreen webkitallowfullscreen></iframe>')
		$('#mDetallefactura').modal('toggle');
	})



	$("#tblReporteventas tbody").on('dblclick','tr',function(){
		var table = $('#tblReporteventas').DataTable();	
		var objeto = table.row(this).data();	
		var datos = { 'idVenta' : objeto.nIDVENTA}
		console.log(objeto);

		$('#idVenta').val(objeto.nIDVENTA);
		$('#fechaVenta').val(objeto.dFECHAVENTA);
		$('#sCliente').val(objeto.sCLIENTE);
		$('#observacionVenta').val(objeto.sOBSERVACION);
		$('#cantidadTotalVenta').html(objeto.nCantidadTotalVenta);
		$('#precioTotalVenta').html(objeto.sCostoTotalVenta);
		$('#observacionVentaPago').val('');
		$('#deudaTotalVenta').html(objeto.sDeudaTotalVenta);
		getDetalleVentaPago(datos);
		getDetalleVenta(datos);

		$('#divCuenta').hide();

	});

	function getDetalleVentaPago(datos){
		$.ajax({
			url: 'cuentacobro/getDetalleVentaPago',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){
				//console.log(data);
				
				var html='';
				var monto = 0;
				$.each(data, function( i, v ) {
					html+='<tr>'+
							'<th>'+v.dFECHAPAGO+'</th>'+
							'<th>'+v.sTIPOPAGO+'</th>'+
							'<th>'+v.sNROCUENTA+'</th>'+
							'<th>'+v.fMONTO+'</th>'+
						'</tr>';
					monto += parseFloat(v.fMONTO);
				});
				$('#montoTotalPago').html(monto);
				$('#tBodyDetallePago').html(html);
			}				
		});
	}

	function getDetalleVenta(datos){
		$.ajax({
			url: 'extorno/getDetalleVenta',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){
				$('#mDetalleVenta').modal('show');
				var html='';
				$.each(data, function( i, v ) {
					html+='<tr>'+
							'<th>'+v.sPRODUCTO+'</th>'+
							'<th>'+v.nCANTIDAD+'</th>'+
							'<th>'+v.fPRECIO+'</th>'+
							'<th>'+v.fCOSTO+'</th>'+
						'</tr>';
				});
				$('#tBodyDetalleVenta').html(html);

				
			}				
		});
	}	



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

