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

		if(codCompra=='' && fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Produccion', {optionsToastr});} 
		else if(codCompra=='' && ((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Produccion', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Produccion', {optionsToastr});}
		else{			
			tablaCompra(codLocal,((codCompra=='') ? 'vacio' : codCompra), fechaInicio, fechaFin);
		}
	});


	function tablaCompra(codLocal=0, codCompra=0, fechaInicio='', fechaFin=''){
		$('#tablaCompra').dataTable().fnDestroy();
		$('#tablaCompra').DataTable({
			
			"order" : [ [ 0, "desc" ]],
			"ajax" : "produccion/getCompras/"+codLocal+'/'+codCompra+'/'+fechaInicio+'/'+fechaFin,
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

	function getDetalleCompra(idCompra){
		$('#tablaDetalleCompra').dataTable().fnDestroy();
		$('#tablaDetalleCompra').DataTable({
			
			"order" : [ [ 0, "desc" ]],
			"ajax" : "produccion/getDetalleCompra/"+idCompra,
			"columns" : [
			{
				"data" : "sPRODUCTO"
			},{
				"data" : "nCANTIDADCOMPRADA"
			},{
				"data" : "nCANTIDADPRODUCIDA"
			},{
				"data" : "nDIFERENCIA"
			},	
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});
		$('#mDetalleCompra').modal('show');
	}

	$("#tablaCompra tbody").on('dblclick','tr',function(){
		
		var table = $('#tablaCompra').DataTable();	
		var objeto = table.row(this).data();		
		//var datos = { 'idCompra' : objeto.IDCOMPRA}

		$('#idCompra').val(objeto.IDCOMPRA);
		$('#fechaCompra').val(objeto.FECHA_COMPRA);
		$('#sCliente').val(objeto.CLIENTE);
		$('#observacionCompra').val(objeto.OBSERVACION);

		$('#observacionProduccion').val('');

		getDetalleCompra(objeto.IDCOMPRA)

		/*$.ajax({
			url: 'produccion/getDetalleCompra',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){
				console.log(data);
				$('#mDetalleCompra').modal('show');
				var html='';
				$.each(data, function( i, v ) {
					html+='<tr>'+
							'<th>'+v.sPRODUCTO+'</th>'+
							'<th>'+v.nCANTIDADCOMPRADA+'</th>'+
							'<th>'+v.nCANTIDADPRODUCIDA+'</th>'+
							'<th>'+v.nDIFERENCIA+'</th>'+
							'<th>'+v.BOTON+'</th>'+
						'</tr>';
				});
				$('#tBodyDetalleCompra').html(html);
			}				
		});*/

	});

	$("#tablaDetalleCompra tbody").on('dblclick','tr',function(){
		var table = $('#tablaDetalleCompra').DataTable();	
		var objeto = table.row(this).data();
		//console.log(objeto)
		$('#titleProducto').html(objeto.sPRODUCTO);
		$('#mDetalleProduccion').modal('show');
		var html='';
		var cantidad=0;
		$.each(objeto.detalleProduccion, function( i, v ) {
			cantidad += parseInt(v.nCANTIDAD);
			html+='<tr>'+
					'<th>'+v.dFECHAPRODUCCION+'</th>'+
					'<th>'+v.nIDPRODUCCION+'</th>'+
					'<th>'+v.sIDUSUARIOCREACION+'</th>'+
					'<th>'+v.nCANTIDAD+'</th>'+
				'</tr>';
		});
		$('#tBodyDetalleProduccion').html(html);
		$('#cantidadTotalProduccion').html(cantidad);
		
	});

	$('#btnExtornar').click(function(){
		var idVenta = $('#idVenta').val();
		var motivo = $('#sMotivo').val();
		if(motivo==''){ toastr['warning']('Ingrese Motivo', 'Extorno', {optionsToastr}); }
		else{

			$.confirm({
				title: 'Extonar Venta !!',
				content: '¿ Desea Continuar ?',
				closeIcon: true,
				closeIconClass: 'fa fa-close' ,
				confirmButton: 'Continuar',
				confirmButtonClass: 'btn-primary',
				cancelButton:'Cancelar',
				icon: 'fa fa-warning',
				animation: 'zoom', 
				confirm: function(){

					var datos = { 'idVenta' : idVenta, 'motivo' : motivo }
					$.ajax({
						url: 'extorno/extornarVenta',  
						type: 'POST',
						data:  datos, 
						cache: false,
						dataType:'json',				
						success: function(data){
							if(data){
								$('#mDetalleVenta').modal('hide');
								toastr['success']('Venta Extornada Correctamente <br> Cod. Extorno: '+data.idExtorno, 'Extorno', {optionsToastr});
								$('#tablaVenta').dataTable().fnClearTable();
							}
						}
					});					

				},cancel: function(){
					$.alert('Extorno Cancelado');		        
				}
			});
		}
	});


});


	