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

	// getEstados();

	function tblReporteventas(codLocal=0, tipoVenta=0, fechaInicio='', fechaFin=''){
		$('#tblReporteventas').dataTable().fnDestroy();		 	
		$('#tblReporteventas').DataTable({

			"footerCallback": function ( row, data, start, end, display ) {
	            var api = this.api(), data;
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\$,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };
	            // Totales por columna desde 0
				let costo = api.column( 4 ).data().reduce( function (a, b) {
					return intVal(a) + intVal(b);
				}, 0 );

 

				 $( api.column( 3 ).footer() ).html('Total:');
				 $( api.column( 4 ).footer() ).html(costo);
 
	        },
			//PARA EXPORTAR			
			dom: 'frtip<"clear"B>',
			buttons: [
				{
					extend: 'collection',
					text: 'Exportar',
					buttons: [
						'copy',
						'excel',
						'csv',
						'pdf',
						'print'
					]
				}
			],

			"order": [[ 0, "desc" ]],
			"ajax" : "reportevendedor/getVentasTable/"+codLocal+'/'+tipoVenta+'/'+fechaInicio+'/'+fechaFin,
			"columns" : [	
				{ "data" : "dFECHAVENTA"},
				{ "data" : "nIDVENTA"},
				{ "data" : "nIDVENTACOMPARTIDA"},
				{ "data" : "sCLIENTE"},
				{ "data" : "sCostoTotalVenta"},
				{ "data" : "sPagoTotalVenta", visible: false},
				{ "data" : "sDeudaTotalVenta", visible: false},
				{ "data" : "OPCIONES", visible: false},				
			],"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
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


	
	// function tblReportecompras(codLocal=0, tipoCompra=0, fechaInicio='', fechaFin=''){
	// 	$('#tblReportecompras').dataTable().fnDestroy();		 	
	// 	$('#tblReportecompras').DataTable({

	// 		"footerCallback": function ( row, data, start, end, display ) {
	//             var api = this.api(), data;
	//             // Remove the formatting to get integer data for summation
	//             var intVal = function ( i ) {
	//                 return typeof i === 'string' ?
	//                     i.replace(/[\$,]/g, '')*1 :
	//                     typeof i === 'number' ?
	//                         i : 0;
	//             };
	// 			// Totales por columna desde 0
				
	// 			let cantidad = api.column( 4 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let costo = api.column( 5 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let pago = api.column( 6 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let deuda = api.column( 7 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			 $( api.column( 3 ).footer() ).html('Total:');
	// 			 $( api.column( 4 ).footer() ).html(cantidad);
	// 			 $( api.column( 5 ).footer() ).html(costo);
	// 			 $( api.column( 6 ).footer() ).html(pago);
	// 			 $( api.column( 7 ).footer() ).html(deuda);
	//         },
	// 		//PARA EXPORTAR			
	// 		dom: 'frtip<"clear"B>',
	// 		buttons: [
	// 			{
	// 				extend: 'collection',
	// 				text: 'Exportar',
	// 				buttons: [
	// 					'copy',
	// 					'excel',
	// 					'csv',
	// 					'pdf',
	// 					'print'
	// 				]
	// 			}
	// 		],


	// 		"order": [[ 0, "desc" ]],
	// 		"ajax" : "reporte/getCompras/"+codLocal+'/'+tipoCompra+'/'+fechaInicio+'/'+fechaFin,
	// 		"columns" : [	

	// 			{"data" : "dFECHACOMPRA"},
	// 			{"data" : "sLOCAL"},
	// 			{"data" : "nIDCOMPRA"},
	// 			{"data" : "sPROVEEDOR"},
	// 			{"data" : "nCantidadTotalCompra"},
	// 			{"data" : "sCostoTotalCompra"},
	// 			{"data" : "sPagoTotalCompra"},
	// 			{"data" : "sDeudaTotalCompra"},
	// 			{"data" : "OPCIONES" ,"visible": false },

	// 		],"language": {
	// 			"url": "/kardex/public/cdn/datatable.spanish.lang"
	// 		} 
	// 	});	
	// }
	

	// function tblReporteCuentasxPagar(fechaInicio, fechaFin){
	// 	$('#tblReporteCuentasxPagar').dataTable().fnDestroy();		 	
	// 	$('#tblReporteCuentasxPagar').DataTable({

	// 		"footerCallback": function ( row, data, start, end, display ) {
	//             var api = this.api(), data;
	//             // Remove the formatting to get integer data for summation
	//             var intVal = function ( i ) {
	//                 return typeof i === 'string' ?
	//                     i.replace(/[\$,]/g, '')*1 :
	//                     typeof i === 'number' ?
	//                         i : 0;
	//             };
	// 			// Totales por columna desde 0
				
	// 			let cantidad = api.column( 3 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let costo = api.column( 4 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let pago = api.column( 5 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let deuda = api.column( 6 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			 $( api.column( 2 ).footer() ).html('Total:');
	// 			 $( api.column( 3 ).footer() ).html(cantidad);
	// 			 $( api.column( 4 ).footer() ).html(costo);
	// 			 $( api.column( 5 ).footer() ).html(pago);
	// 			 $( api.column( 6 ).footer() ).html(deuda);
	//         },
	// 		//PARA EXPORTAR			
	// 		dom: 'frtip<"clear"B>',
	// 		buttons: [
	// 			{
	// 				extend: 'collection',
	// 				text: 'Exportar',
	// 				buttons: [
	// 					'copy',
	// 					'excel',
	// 					'csv',
	// 					'pdf',
	// 					'print'
	// 				]
	// 			}
	// 		],


	// 		"order": [[ 0, "desc" ]],
	// 		"ajax" : "reporte/getCuentasPorPagar/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
	// 		"columns" : [	
 	// 			{"data" : "dFECHACOMPRA"},
	// 			{"data" : "nIDCOMPRA"},
	// 			{"data" : "sPROVEEDOR"},
	// 			{"data" : "nCantidadTotalCompra"},
	// 			{"data" : "sCostoTotalCompra"}, 
	// 			{"data" : "sPagoTotalCompra"}, 
	// 			{"data" : "sDeudaTotalCompra"},
	// 		],"language": {
	// 			"url": "/kardex/public/cdn/datatable.spanish.lang"
	// 		} 
	// 	});	
	// }


	// function tblReporteCuentasxCobrar(fechaInicio, fechaFin){
	// 	$('#tblReporteCuentasxCobrar').dataTable().fnDestroy();		 	
	// 	$('#tblReporteCuentasxCobrar').DataTable({

	// 		"footerCallback": function ( row, data, start, end, display ) {
	//             var api = this.api(), data;
	//             // Remove the formatting to get integer data for summation
	//             var intVal = function ( i ) {
	//                 return typeof i === 'string' ?
	//                     i.replace(/[\$,]/g, '')*1 :
	//                     typeof i === 'number' ?
	//                         i : 0;
	//             };
	// 			// Totales por columna desde 0
				
	// 			let cantidad = api.column( 3 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let costo = api.column( 4 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let pago = api.column( 5 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let deuda = api.column( 6 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			 $( api.column( 2 ).footer() ).html('Total:');
	// 			 $( api.column( 3 ).footer() ).html(cantidad);
	// 			 $( api.column( 4 ).footer() ).html(costo);
	// 			 $( api.column( 5 ).footer() ).html(pago);
	// 			 $( api.column( 6 ).footer() ).html(deuda);
	//         },
	// 		//PARA EXPORTAR			
	// 		dom: 'frtip<"clear"B>',
	// 		buttons: [
	// 			{
	// 				extend: 'collection',
	// 				text: 'Exportar',
	// 				buttons: [
	// 					'copy',
	// 					'excel',
	// 					'csv',
	// 					'pdf',
	// 					'print'
	// 				]
	// 			}
	// 		],


	// 		"order": [[ 0, "desc" ]],
	// 		"ajax" : "reporte/getCuentasPorCobrar/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
	// 		"columns" : [	
	// 			{"data" : "dFECHAVENTA"},
	// 			{"data" : "nIDVENTA"},
	// 			{"data" : "sCLIENTE"},
	// 			{"data" : "nCantidadTotalVenta"},
	// 			{"data" : "sCostoTotalVenta"},
	// 			{"data" : "sPagoTotalVenta"},
	// 			{"data" : "sDeudaTotalVenta"},
	// 		],"language": {
	// 			"url": "/kardex/public/cdn/datatable.spanish.lang"
	// 		} 
	// 	});	
	// }

	// function getInversion(){
	// 	$('#tblReporteInversion').dataTable().fnDestroy();		 	
	// 	$('#tblReporteInversion').DataTable({

	// 		"footerCallback": function ( row, data, start, end, display ) {
	//             var api = this.api(), data;
	//             // Remove the formatting to get integer data for summation
	//             var intVal = function ( i ) {
	//                 return typeof i === 'string' ?
	//                     i.replace(/[\$,]/g, '')*1 :
	//                     typeof i === 'number' ?
	//                         i : 0;
	//             };
	// 			// Totales por columna desde 0
				
	// 			let cantidad = api.column( 2 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let costo = api.column( 3 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			 $( api.column( 1 ).footer() ).html('Total:');
	// 			 $( api.column( 2 ).footer() ).html(cantidad);
	// 			 $( api.column( 3 ).footer() ).html(costo);

	//         },
	// 		//PARA EXPORTAR			
	// 		dom: 'frtip<"clear"B>',
	// 		buttons: [
	// 			{
	// 				extend: 'collection',
	// 				text: 'Exportar',
	// 				buttons: [
	// 					'copy',
	// 					'excel',
	// 					'csv',
	// 					'pdf',
	// 					'print'
	// 				]
	// 			}
	// 		],

	// 		"order": [[ 0, "desc" ]],
	// 		"ajax" : "reporte/getInversion/"+$('#codLocal').val(),
	// 		"columns" : [	
	// 			{"data" : "nIDPRODUCTO"},
	// 			{"data" : "sNOMBRE"},
	// 			{"data" : "nCANTIDAD"},
	// 			{"data" : "nTOTAL"},
	// 			{"data" : "avgPorCantidadPrecio"},
	// 			{"data" : "avgPrecio" ,"visible": false },
	// 		],"language": {
	// 			"url": "/kardex/public/cdn/datatable.spanish.lang"
	// 		} 
	// 	});	
	// }

	// getInversion();


	// function tblReporteBalance(fechaInicio, fechaFin){
	// 	$('#tblReporteBalance').dataTable().fnDestroy();		 	
	// 	$('#tblReporteBalance').DataTable({

	// 		"footerCallback": function ( row, data, start, end, display ) {
	//             var api = this.api(), data;
	//             // Remove the formatting to get integer data for summation
	//             var intVal = function ( i ) {
	//                 return typeof i === 'string' ?
	//                     i.replace(/[\$,]/g, '')*1 :
	//                     typeof i === 'number' ?
	//                         i : 0;
	//             };
	// 			// Totales por columna desde 0
				
	// 			let cantidad = api.column( 1 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let cantidadProductos = api.column( 2 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let ventaTotal = api.column( 3 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let pagoEfectivo = api.column( 4 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let pagoDeposito = api.column( 5 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			let credito = api.column( 6 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );
	// 			let ganancia = api.column( 7 ).data().reduce( function (a, b) {
	// 				return intVal(a) + intVal(b);
	// 			}, 0 );

	// 			 $( api.column( 0 ).footer() ).html('Total:');
	// 			 $( api.column( 1 ).footer() ).html(cantidad);
	// 			 $( api.column( 2 ).footer() ).html(cantidadProductos);
	// 			 $( api.column( 3 ).footer() ).html(ventaTotal);
	// 			 $( api.column( 4 ).footer() ).html(pagoEfectivo);
	// 			 $( api.column( 5 ).footer() ).html(pagoDeposito);
	// 			 $( api.column( 6 ).footer() ).html(credito);
	// 			 $( api.column( 7 ).footer() ).html(ganancia);

	//         },
	// 		//PARA EXPORTAR			
	// 		dom: 'frtip<"clear"B>',
	// 		buttons: [
	// 			{
	// 				extend: 'collection',
	// 				text: 'Exportar',
	// 				buttons: [
	// 					'copy',
	// 					'excel',
	// 					'csv',
	// 					'pdf',
	// 					'print'
	// 				]
	// 			}
	// 		],


	// 		"order": [[ 0, "desc" ]],
	// 		"ajax" : "reporte/getReporteBalance/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
	// 		"columns" : [	
 
	// 			{"data" : "FECHA"},
	// 			{"data" : "ventas"},
	// 			{"data" : "ventasProductos"},
	// 			{"data" : "ventasCosto"},
	// 			{"data" : "efectivo"},
	// 			{"data" : "deposito"},
	// 			{"data" : "credito"},
	// 			{"data" : "ganancia"},
	// 		],"language": {
	// 			"url": "/kardex/public/cdn/datatable.spanish.lang"
	// 		} 			
	// 	});	
	// }


	// $("#tblReporteBalance tbody").on('dblclick','tr',function(){
	// 	var table = $('#tblReporteBalance').DataTable();	
	// 	var objeto = table.row(this).data();

	// 	// var datos = { 'idVenta' : objeto.nIDVENTA}		
	// 	var montoEfectivo = objeto.detalleCuentas.EFECTIVO[0].fmonto;

	// 	html='';

	// 	$.each(objeto.detalleCuentas.DEPOSITO, function( i, v ) {

	// 		let cuenta=v.cuenta.match(/.{1,4}/g);

	// 		html+='<a href="#" class="box m-a-0 p-x-3 p-y-2 b-t-1 bg-white">'+
	// 			'<div class="box-cell valign-middle" style="width: 0px;">'+
	// 			'<i class="fa fa-cc-visa text-muted font-size-28"></i>'+
	// 			'</div>'+
	// 			'<div class="box-cell">'+
	// 			'<div class="pull-xs-right font-size-18"><small class="font-size-13">S/ </small><strong>'+v.fmonto+'</strong></div>'+
	// 			'<div class="text-muted font-size-14">'+cuenta.join(' ')+'</div>'+
	// 			'</div>'+
	// 			'</a>'
	// 			;
	// 	});

	// 	$('#montoEfectivo').html(montoEfectivo);
	// 	$('#divPagosCuentas').html(html);

	// 	$('#mDetallePagos').modal('show');
	// 	// $('#idVenta').val(objeto.nIDVENTA);
	// 	// $('#fechaVenta').val(objeto.dFECHAVENTA);
	// 	// $('#sCliente').val(objeto.sCLIENTE);
	// 	// $('#observacionVenta').val(objeto.sOBSERVACION);

	// });

	// // $('#btnBuscar').click(function(){
	// // 	var fechaInicio = $('#fechaInicio1').val();
	// // 	var fechaFin = $('#fechaFin1').val();
	// // 	if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
	// // 		tblReporteventas(fechaInicio, fechaFin);			
	// // 	}
	// // });
	


	$('#btnBuscarCompras').click(function(){
		var codLocal = $('#codLocal').val();
		var tipoCompra = $('#tipoCompra').val();
 		var fechaInicio = $('#fechaInicio2').val();
		var fechaFin = $('#fechaFin2').val();		

		if(fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Cuentas por Cobrar', {optionsToastr});} 
		else if(((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Cuentas por Cobrar', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Cuentas por Cobrar', {optionsToastr});}
		else{			
			tblReportecompras(codLocal,tipoCompra, fechaInicio, fechaFin);
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
		var datos = { 'idVenta' : objeto.nIDVENTACOMPARTIDA,}		

		console.log(datos);
		$('#idVenta').val(objeto.nIDVENTA);
		$('#idVentaCompartida').val(objeto.nIDVENTACOMPARTIDA);
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


	$("#tblReportecompras tbody").on('dblclick','tr',function(){
		
		var table = $('#tblReportecompras').DataTable();	
		var objeto = table.row(this).data();		
		var datos = { 'idcompra' : objeto.nIDCOMPRA}

		$('#idcompra').val(objeto.nIDCOMPRA);
		$('#fechaCompra').val(objeto.dFECHACOMPRA);
		$('#sProveedor').val(objeto.sPROVEEDOR);
		$('#observacionCompra').val(objeto.sOBSERVACION);
		$('#cantidadTotalCompra').html(objeto.nCantidadTotalCompra);
		$('#precioTotalCompra').html(objeto.sCostoTotalCompra);
		$('#observacionCompraPago').val('');
		$('#deudaTotalCompra').html(objeto.sDeudaTotalCompra);
		getDetalleCompraPago(datos);
		getDetalleCompra(datos);
 

	});

	function getDetalleCompraPago(datos){
		$.ajax({
			url: 'cuentapago/getDetalleCompraPago',  
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
				$('#montoTotalPagoCompra').html(monto);
				$('#tBodyDetallePagoCompras').html(html);
			}				
		});
	}

	function getDetalleCompra(datos){
		$.ajax({
			url: 'anulacion/getDetalleCompra',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){
				$('#mDetalleCompra').modal('show');
				var html='';
				$.each(data, function( i, v ) {
					html+='<tr>'+
							'<th>'+v.sPRODUCTO+'</th>'+
							'<th>'+v.nCANTIDAD+'</th>'+
							'<th>'+v.fPRECIO+'</th>'+
							'<th>'+v.fCOSTO+'</th>'+
						'</tr>';
				});
				$('#tBodyDetalleCompra').html(html);

				
			}				
		});
	}	



	function getDetalleVentaPago(datos){
		$.ajax({
			url: 'reportevendedor/getDetalleVentaPago',  
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
			url: 'reportevendedor/getDetalleVenta',  
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


	
	$("#tblReporteCuentasxPagar tbody").on('dblclick','tr',function(){
		
		var table = $('#tblReporteCuentasxPagar').DataTable();	
		var objeto = table.row(this).data();		
		var datos = { 'idcompra' : objeto.nIDCOMPRA}

		$('#idcompra').val(objeto.nIDCOMPRA);
		$('#fechaCompra').val(objeto.dFECHACOMPRA);
		$('#sProveedor').val(objeto.sPROVEEDOR);
		$('#observacionCompra').val(objeto.sOBSERVACION);
		$('#cantidadTotalCompra').html(objeto.nCantidadTotalCompra);
		$('#precioTotalCompra').html(objeto.sCostoTotalCompra);
		$('#observacionCompraPago').val('');
		$('#deudaTotalCompra').html(objeto.sDeudaTotalCompra);
		getDetalleCompraPago(datos);
		getDetalleCompra(datos);
 

	});


	
	$("#tblReporteCuentasxCobrar tbody").on('dblclick','tr',function(){
		var table = $('#tblReporteCuentasxCobrar').DataTable();	
		var objeto = table.row(this).data();	
		var datos = { 'idVenta' : objeto.nIDVENTA}		

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


	$('#btnBuscar9').click(function(){
		var fechaInicio = $('#fechaInicio9').val();
		var fechaFin = $('#fechaFin9').val();
		if(fechaInicio <= fechaFin && fechaInicio!='' && fechaFin!=''){
			tblReporteVentasPorVendedor(fechaInicio, fechaFin);			
		}
	});


	function tblReporteVentasPorVendedor(fechaInicio, fechaFin){
		$('#tblReporteVentasPorVendedor').dataTable().fnDestroy();		 	
		$('#tblReporteVentasPorVendedor').DataTable({

			//PARA EXPORTAR			
			dom: 'frtip<"clear"B>',
			buttons: [
				{
					extend: 'collection',
					text: 'Exportar',
					buttons: [
						'copy',
						'excel',
						'csv',
						'pdf',
						'print'
					]
				}
			],
			"order": [[ 0, "desc" ]],
			"ajax" : "reporte/getVentasXVendedor/"+fechaInicio+"/"+fechaFin+"/"+$('#codLocal').val(),
			"columns" : [ 
				{"data" : "dFECHAVENTA"},
				{"data" : "sVENDEDOR"},
				{"data" : "nCantidadTotalVenta"},
				{"data" : "sCostoTotalVenta"},
			],"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 			
		});	
	}
	
	

});

