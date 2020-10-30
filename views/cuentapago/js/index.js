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

		if(codCompra=='' && fechaInicio=='' && fechaFin==''){ toastr['warning']('Ingrese almenos un campo de busqueda', 'Cuentas por Pagar', {optionsToastr});} 
		else if(codCompra=='' && ((fechaInicio!='' && fechaFin=='') || (fechaInicio=='' && fechaFin!=''))){ toastr['warning']('Ingrese 2 fechas', 'Cuentas por Pagar', {optionsToastr} );}
		else if(fechaInicio > fechaFin){ toastr['warning']('La fecha Inicio debe no debe superar la fecha Fin', 'Cuentas por Pagar', {optionsToastr});}
		else{			
			getCuentasPorPagar(codLocal,((codCompra=='') ? 'vacio' : codCompra), fechaInicio, fechaFin);
		}
	});


	function getCuentasPorPagar(codLocal=0, codCompra=0, fechaInicio='', fechaFin=''){
		$('#tblCuentasPorPagar').dataTable().fnDestroy();
		$('#tblCuentasPorPagar').DataTable({

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
			"ajax" : "cuentapago/getCuentasPorPagar/"+codLocal+'/'+codCompra+'/'+fechaInicio+'/'+fechaFin,
			"columns" : [
			{
				"data" : "dFECHACOMPRA"
			},{
				"data" : "nIDCOMPRA"
			},{
				"data" : "sPROVEEDOR"
			},{
				"data" : "sCostoTotalCompra"
			},{
				"data" : "sPagoTotalCompra"
			},{
				"data" : "sDeudaTotalCompra"
			},
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$("#tblCuentasPorPagar tbody").on('dblclick','tr',function(){
		
		var table = $('#tblCuentasPorPagar').DataTable();	
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

		$('#divCuenta').hide();

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
				$('#montoTotalPago').html(monto);
				$('#tBodyDetallePago').html(html);
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

		if(fechaPago==''){ toastr['warning']('Ingrese Fecha de Pago', 'Cuentas por Pagar', {optionsToastr}); }
		else if(formaPago==''){ toastr['warning']('Ingrese Forma de Pago', 'Cuentas por Pagar', {optionsToastr}); }
		else if(formaPago=='02' && cuenta==''){ toastr['warning']('Ingrese Nro de Cuenta', 'Cuentas por Pagar', {optionsToastr}); }
		else if(montopago=='' || montopago==0 ){ toastr['warning']('Ingrese Monto de Pago', 'Cuentas por Pagar', {optionsToastr}); }
		else{

			var formData = new FormData($("#form_compra_detalle")[0]);			

			$.confirm({
				title: 'Pagar Cuenta !!',
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
						url: 'cuentapago/addPago',  
						type: 'POST',
						data:  formData, 
						cache: false,
						contentType: false,
						processData: false,
						dataType:'json',
						success: function(data){
							console.log(data);
							if(data){
								$('#mDetalleCompra').modal('hide');
								toastr['success']('Pago Efectuado Correctamente <br> Cod. Pago: '+data.idPago, 'Cuenta por Pagar', {optionsToastr});
								$('#tblCuentasPorPagar').dataTable().fnClearTable();
								$('#form_compra_detalle')[0].reset();
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


	