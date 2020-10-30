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

		if(codVenta=='' && fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Cuentas por Cobrar', {optionsToastr});} 
		else if(codVenta=='' && ((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Cuentas por Cobrar', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Cuentas por Cobrar', {optionsToastr});}
		else{			
			getCuentasPorPagar(codLocal,((codVenta=='') ? 'vacio' : codVenta), fechaInicio, fechaFin);
		}
	});


	function getCuentasPorPagar(codLocal=0, codVenta=0, fechaInicio='', fechaFin=''){
		$('#tblCuentasPorCobrar').dataTable().fnDestroy();
		$('#tblCuentasPorCobrar').DataTable({

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
			"ajax" : "cuentacobro/getCuentasPorCobrar/"+codLocal+'/'+codVenta+'/'+fechaInicio+'/'+fechaFin,
			"columns" : [
			{
				"data" : "dFECHAVENTA"
			},{
				"data" : "nIDVENTA"
			},{
				"data" : "sCLIENTE"
			},{
				"data" : "sCostoTotalVenta"
			},{
				"data" : "sPagoTotalVenta"
			},{
				"data" : "sDeudaTotalVenta"
			},
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$("#tblCuentasPorCobrar tbody").on('dblclick','tr',function(){
		
		var table = $('#tblCuentasPorCobrar').DataTable();	
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

	$(function(){
		$.post('compra/getTipopago',{},function(data){
			$('#formaPago').html(data);
		});
	});

	$('#formaPago').change(function(){
		var idProducto = $(this).val();
		if(idProducto=="02"){
			$('#divCuenta').show();
			$('#cuenta').val('');
		}else{
			$('#divCuenta').hide();
			$('#cuenta').val('');
		}
	});

	$('#cuenta').typeahead({
		displayText: function(item) {
			return item.label
		},
		afterSelect: function(item) {
			this.$element[0].value = item.label
			$('#idCuenta').val(item.value);
		},
		source: function (query, process) {
			return $.getJSON('compra/autocuenta', { query: query }, function(data) {
				process(data)
		  	});
		}
	});

	$("#cuenta").keypress(function(){
		$('#idCuenta').val('');
	});

	$('#btnPagar').click(function(){

		var observacionCompraPago = $('#observacionCompraPago').val();
		var fechaPago = $('#fechaPago').val();
		var formaPago = $('#formaPago').val();
		var cuenta = $('#cuenta').val();
		var montopago = $('#montopago').val();

		if(fechaPago==''){ toastr['warning']('Ingrese Fecha de Pago', 'Cuentas por Cobrar', {optionsToastr}); }
		else if(formaPago=='' || formaPago==null){ toastr['warning']('Ingrese Forma de Pago', 'Cuentas por Cobrar', {optionsToastr}); }
		else if(formaPago=='02' && cuenta==''){ toastr['warning']('Ingrese Nro de Cuenta', 'Cuentas por Cobrar', {optionsToastr}); }
		else if(montopago=='' || montopago==0 ){ toastr['warning']('Ingrese Monto de Pago', 'Cuentas por Cobrar', {optionsToastr}); }
		else{

			var formData = new FormData($("#form_venta_detalle")[0]);			

			$.confirm({
				title: 'Cobrar Cuenta !!',
				content: '¿ Desea Continuar ?',
				closeIcon: true,
				closeIconClass: 'fa fa-close' ,
				confirmButton: 'Continuar',
				confirmButtonClass: 'btn-primary',
				cancelButton:'Cancelar',
				icon: 'fa fa-warning',
				animation: 'zoom', 
				confirm: function(){

					$.ajax({
						url: 'cuentacobro/addPago',  
						type: 'POST',
						data:  formData, 
						cache: false,
						contentType: false,
						processData: false,
						dataType:'json',
						success: function(data){
							console.log(data);
							if(data){
								$('#mDetalleVenta').modal('hide');
								toastr['success']('Pago Efectuado Correctamente <br> Cod. Pago: '+data.idPago, 'Cuenta por Cobrar', {optionsToastr});
								$('#tblCuentasPorCobrar').dataTable().fnClearTable();
								$('#form_venta_detalle')[0].reset();
							}
						}
					});
				},cancel: function(){
					$.alert('Pago Cancelado');		        
				}
			});
		}
	});

});


	