$(document).on('ready',function(){

	optionsToastr = {	closeButton: true,
						progressBar: true,
						preventDuplicates: true,
						newestOnTop: true,};

	$('#btnBuscar').click(function(){

		var codLocal = $('#codLocal').val();
		var codCompra = $('#codCompra').val();
		var fechaInicio = $('#fechaInicio').val();
		var fechaFin = $('#fechaFin').val();		

		if(codCompra=='' && fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Anulacion', {optionsToastr});} 
		else if(codCompra=='' && ((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Anulacion', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Anulacion', {optionsToastr});}
		else{			
			tablaCompras(codLocal,((codCompra=='') ? 'vacio' : codCompra), fechaInicio, fechaFin);
		}
	});


	function tablaCompras(codLocal=0, codCompra=0, fechaInicio='', fechaFin=''){
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
			"ajax" : "anulacion/getCompras/"+codLocal+'/'+codCompra+'/'+fechaInicio+'/'+fechaFin,
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

	$("#tablaCompra tbody").on('dblclick','tr',function(){
		
		var table = $('#tablaCompra').DataTable();	
		var objeto = table.row(this).data();		
		var datos = { 'idcompra' : objeto.IDCOMPRA}

		$('#idcompra').val(objeto.IDCOMPRA);
		$('#fechaCompra').val(objeto.FECHA_COMPRA);
		$('#sProveedor').val(objeto.PROVEEDOR);
		$('#observacion').val(objeto.OBSERVACION);
		$('#cantidadTotalCompra').html(objeto.CANTIDAD_TOTAL_COMPRA);
		$('#precioTotalCompra').html(objeto.COSTO_TOTAL_COMPRA);

		$('#motivo').val('');

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


		$.ajax({
			url: 'anulacion/getDetalleVenta',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){
				var html='';
				$.each(data, function( i, v ) {
					html+='<tr>'+
							'<th>'+v.dFECHAVENTA+'</th>'+
							'<th>'+v.nIDVENTA+'</th>'+
							'<th>'+v.nCANTIDAD+'</th>'+
							'<th>'+v.fIMPORTE+'</th>'+
						'</tr>';
				});
				$('#tBodyDetalleVenta').html(html);
			}				
		});

	});

	$('#btnAnular').click(function(){
		var idcompra = $('#idcompra').val();
		var motivo = $('#motivo').val();
		if(motivo==''){ toastr['warning']('Ingrese Motivo', 'Anulacion', {optionsToastr}); }
		else{

			$.confirm({
				title: 'Anular Compra !!',
				content: '¿ Desea Continuar ?',
				closeIcon: true,
				closeIconClass: 'fa fa-close' ,
				confirmButton: 'Continuar',
				confirmButtonClass: 'btn-primary',
				cancelButton:'Cancelar',
				icon: 'fa fa-warning',
				animation: 'zoom', 
				confirm: function(){

					var datos = { 'idcompra' : idcompra, 'motivo' : motivo }
					$.ajax({
						url: 'anulacion/anularCompra',  
						type: 'POST',
						data:  datos, 
						cache: false,
						dataType:'json',				
						success: function(data){
							if(data.idAnulacion){
								$('#mDetalleCompra').modal('hide');
								toastr['success']('Compra Anulada Correctamente <br> Cod. Anulacion: '+data.idAnulacion, 'Anulacion', {optionsToastr});
								$('#tablaCompra').dataTable().fnClearTable();
							}else{
								//$('#mDetalleCompra').modal('hide');
								toastr['warning']('La compra no puede ser anulada, porque existe ventas', 'Anulacion', {optionsToastr});
							}
						}
					});					

				},cancel: function(){
					$.alert('Anulacion Cancelada');		        
				}
			});
		}
	});


});


	