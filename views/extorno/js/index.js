$(document).on('ready',function(){

	optionsToastr = {	closeButton: true,
						progressBar: true,
						preventDuplicates: true,
						newestOnTop: true,};

	$('#btnBuscar').click(function(){

		var codLocal = $('#codLocal').val();
		var codVenta = $('#codVenta').val();
		var fechaInicio = $('#fechaInicio').val();
		var fechaFin = $('#fechaFin').val();		

		if(codVenta=='' && fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Extorno', {optionsToastr});} 
		else if(codVenta=='' && ((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Extorno', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Extorno', {optionsToastr});}
		else{			
			tablaVentas(codLocal,((codVenta=='') ? 'vacio' : codVenta), fechaInicio, fechaFin);
		}
	});


	function tablaVentas(codLocal=0, codVenta=0, fechaInicio='', fechaFin=''){
		$('#tablaVenta').dataTable().fnDestroy();
		$('#tablaVenta').DataTable({

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
			"ajax" : "extorno/getVentas/"+codLocal+'/'+codVenta+'/'+fechaInicio+'/'+fechaFin,
			"columns" : [
			{
				"data" : "FECHA_VENTA"
			},{
				"data" : "IDVENTA"
			},{
				"data" : "CLIENTE"
			},{
				"data" : "CANTIDAD_TOTAL_VENTA"
			},{
				"data" : "COSTO_TOTAL_VENTA"
			},		
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$("#tablaVenta tbody").on('dblclick','tr',function(){
		
		var table = $('#tablaVenta').DataTable();	
		var objeto = table.row(this).data();		
		var datos = { 'idVenta' : objeto.IDVENTA}

		$('#idVenta').val(objeto.IDVENTA);
		$('#fechaVenta').val(objeto.FECHA_VENTA);
		$('#sCliente').val(objeto.CLIENTE);
		$('#observacion').val(objeto.OBSERVACION);
		$('#cantidadTotalVenta').html(objeto.CANTIDAD_TOTAL_VENTA);
		$('#precioTotalVenta').html(objeto.COSTO_TOTAL_VENTA);

		$('#sMotivo').val('');

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


	