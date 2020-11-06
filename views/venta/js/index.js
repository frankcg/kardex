$(document).on('ready',function(){


	optionsToastr = {	closeButton: true,
						progressBar: true,
						preventDuplicates: true,
						newestOnTop: true,};

	function showItems() {
		$('#productos').show();
		$('#monto').show();
		$('#montorestante').show();
	};
		
	function hideItems() {
		$('#productos').hide();
		$('#monto').hide();
		$('#divCuenta').hide();
		$('#montorestante').hide();
		$('#divPreciosAnt').hide();

		
	  };

	hideItems();

	function setTemplate (textColor) {
		if (!textColor.id) { return textColor.text; }			
			var textColor = $('<span>'+ textColor.text +  '<span style="color:red;">' +textColor.title +'</span></span>' );
			return textColor;
	};
	
	function getComboProductos(codLocal) {

		try{
			$('#idProducto').select2('destroy');
		}catch(err) {
		}
		
		$(function(){
			$.post('venta/getComboProductos/'+codLocal,{},function(data){
				
				$('#dividProducto').show();
				$('#divProductoloading').hide();
					$('#idProducto').html(data);
					$('#idProducto').select2({
						placeholder: 'Seleccione un Producto',
						templateResult: setTemplate,
						templateSelection: setTemplate,
						dropdownAutoWidth : 'true',
					  });
			});
		});
	}

	$('#radVentacompartida').change(function(){
		var radioValue = $("input[name='radVentacompartida']:checked").val();

		$('#dividProducto').hide();
		$('#divProductoloading').show();

		if(radioValue == "on"){
			getComboProductos('vacio');
		}else{
			getComboProductos($('#codLocal').val());
		}
	})

	getComboProductos($('#codLocal').val());

	$(function(){
		$.post('venta/getTipopago',{},function(data){
				$('#formaPago').html(data);
		});
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
		  return $.getJSON('venta/autocuenta', { query: query }, function(data) {
			process(data)
		  })
		}   
	});

	$("#cuenta").keypress(function(){
		$('#idCuenta').val('');		
	});
	
	$("#idCliente").val("0000001");
	$("#cliente").val("CLIENTE GENERAL");

	$('#cliente').typeahead({
		displayText: function(item) {
			 return item.label
		},
		afterSelect: function(item) {
			this.$element[0].value = item.label
			$('#idCliente').val(item.value);
		},
		source: function (query, process) {
		  return $.getJSON('venta/autocliente', { query: query }, function(data) {
			process(data)
		  });
		}   
	})

	$("#cliente").keypress(function(){
		$('#idCliente').val('');		
	});
	
	$('#formaPago').change(function(){
		var idProducto = $(this).val();		
		if(idProducto=="02"){
			$('#divCuenta').show();
		}else{
			$('#divCuenta').hide();
			$('#cuenta').val('');
		}
	});


	$('#idProductoloading').show();	

	$('#idProducto').change(function(){
		var nombrecart = $('#idProducto').find(':selected').attr('id2');
		$('#cartNombre').val(nombrecart);
		
		//prodiccion off
		$('#divPreciosAnt').show();
		var idProducto = $(this).val();
		promVentaProductos(idProducto);
	});

	function promVentaProductos(idProducto){
		datos = {"idProducto" : idProducto };
		$.ajax({
			url: 'venta/promVentaProductos',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			success: function(data){				 
				var tableProducts='';
				$.each(data, function( i, v ) {
					precioVentaPromedio = v.AVG;
					tableProducts += '<div class="widget-notifications-description"><strong>Promedio  : S/ </strong><a class="etPrecio">'+v.AVG+'</a></div>'
									+'<div class="widget-notifications-description"><strong>Ultimo  : S/ </strong><a class="etPrecio">'+v.LAST+'</a></div>'
									+'<div class="widget-notifications-description"><strong>Minimo  : S/ </strong><a class="etPrecio">'+v.MIN+'</a></div>'
									+'<div class="widget-notifications-description"><strong>Maximo  : S/ </strong><a class="etPrecio">'+v.MAX+'</a></div>';
				});
				$('#htmlPrecios').html(tableProducts);
			}				
		});
	}

	$("#divPreciosAnt").on('click', '.etPrecio', function(){
		var valor = $(this).text();
		$('#precioCompra').val(valor);
	})

	function addProduct(perdida){
		var idLocal 		= $('#idProducto').find(':selected').attr('id4');
		var formData = new FormData($("#formVenta")[0]);
		formData.append("perdida",perdida);
		formData.append("idLocal",idLocal);
		$.ajax({
			url: 'venta/addproductCart',  
			type: 'POST',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){
				obtenerCart();
				calc_total();
				// $('#mAddCompra').modal('hide');
				// if(data=='ok'){
				// 	$('#formCompra')[0].reset();
				// 	toastr['success']('Se registro correctamente', 'venta', {
				//       closeButton: true,
				//       progressBar: true,
				//       preventDuplicates: true,
				//       newestOnTop: true,
				//     });
				//     //toastr['success']('Se registro correctamente', 'Usuario');
				// 	tablaCompras();
					
				// }else if(data=='error'){
				// 	$('#msj_compra').html('Ha ocurrido un Error. Intente de Nuevo!!');
				// }else{
				// 	$('#msj_compra').html(data);
				// }		
			}				
		});
	}

	$('#btn_add_compra').click(function(){
		
		var nombre 			= $('#cartNombre').val();
		var idnombre 		= $('#idProducto').find(':selected').attr('value');
		var cantidad 		= $('#cantidad').val();
		var stock 			= $('#idProducto').find(':selected').attr('id3');
		var precioCompra 	= $('#precioCompra').val();
		var idLocal 		= $('#idProducto').find(':selected').attr('id4');

		var stockActual = parseInt(stock) - parseInt(cantidad);
		var formData = new FormData($("#formVenta")[0]);
		formData.append("idLocal",idLocal);

		var outputLog = {}, iterator = formData.entries(), end = false;
		while(end == false) {
		  	var item = iterator.next();
		   	if(item.value!=undefined) {
		    	outputLog[item.value[0]] = item.value[1];
		   	} else if(item.done==true) {
		    	end = true;
		   	}
		}

		if(nombre == ''){ toastr['warning']('Ingrese un Producto', 'Paso 1', {optionsToastr});}
		else if(cantidad == ''){ toastr['warning']('Ingrese una Cantidad', 'Paso 1', {optionsToastr});}
		else if(precioCompra == ''){ toastr['warning']('Ingrese un precio', 'Paso 1', {optionsToastr});}
		else if(stockActual < 0){ toastr['warning']('la cantidad ingresada es superior al Stock', 'Paso 1', {optionsToastr});}
		else{
			$('#msj_compra').html('');
			
			pCompra = parseFloat(precioCompra);
			pVenta = parseFloat(precioVentaPromedio);

			if(pCompra < pVenta){
				$.confirm({
					title: 'Precio menor al Promedio de Compra!!',
					content: '¿ Desea Continuar ?',
					closeIcon: true,
					closeIconClass: 'fa fa-close' ,
					confirmButton: 'Continuar',
					confirmButtonClass: 'btn-primary',
					cancelButton:'Cancelar',
					icon: 'fa fa-alert',
					animation: 'zoom', 
					confirm: function(){
						addProduct('1');
					},cancel: function(){
						$.alert('Producto NO agregado.');		        
					}
				});
			}else{
				addProduct('0');
			}
		}
	});

	function obtenerCart(){
		$.ajax({
			url: 'venta/showproductCart',  
			type: 'GET',
			data: html,
			success: function(data){				
				$("#ventasCart").html();
				$("#ventasCart").html(data);
				calc_total();				
			}
		});
	}

	function obtenerPagos(){
		$.ajax({
			url: 'venta/showpaymentCart',  
			type: 'GET',
			data: html,
			success: function(data){
				$("#pagosCart").html();
				$("#pagosCart").html(data);
				calc_totalPagos()				
			}
		});
	}

	function calc_total(){
		var sum = 0;
		items =0;

		$(".total").each(function(){
		  sum += parseFloat($(this).text());
		  items = items + 1;
		});

		ventaTotal = sum;		

		$('#ventaTotal').text(sum);
		$('#ventaTotal2').text(sum);
		
		if(sum>0){
			showItems();
			$('#monto').text(sum);
			$('#productos').text(items);
		}else{
			hideItems();
		}		
	}

	  
	function calc_totalPagos(){
		var sum = 0;
		itemsPagos =0;
		$(".pagototal").each(function(){
		  	sum += parseFloat($(this).text());
		  	itemsPagos = itemsPagos + 1;
		});

		pagototal = ventaTotal - sum;
		
		$('#montopago').val(pagototal);

		if(pagototal == ventaTotal){
			$('#montorestante').hide();
		}
		
		if(sum>0){
			showItems();
			$('#montorestante').text(pagototal);
			$('#montopago').val(pagototal);
		}else{
			$('#montorestante').text(pagototal);
			$('#montopago').val(pagototal);
		}
	}

	
	obtenerCart();
	calc_total();
	obtenerPagos();
	calc_totalPagos();
	  

  	$(function() {

		$('#wizard-basic').pxWizard();
	  	$('#wizard-basic').on('stepchange.px.wizard', function(e, data) {		
			obtenerCart();
			calc_total();
			var stepIndex = $('#wizard-basic').pxWizard('getActivePane');

			if (data.nextStepIndex == 2 ) { 
				var montopagar = $('#monto').text();
				$('#montopago').val(montopagar);
			}
	  	});
  	});


	$('#btn_add_pago').click(function(){
		var formaPago 		= $('#formaPago').val();
		var cuenta 			= $('#cuenta').val();
		var montopago 		= parseFloat($('#montopago').val());		
		var formData = new FormData($("#formPago")[0]);
		formData.append("montoapagar", ventaTotal);

		var outputLog = {}, iterator = formData.entries(), end = false;
		while(end == false) {
		   	var item = iterator.next();
		   	if(item.value!=undefined) {
		       	outputLog[item.value[0]] = item.value[1];
		   	} else if(item.done==true) {
		      	end = true;
		   	}
		}

		if(formaPago == ""){ $('#msj_compra_producto').html('Ingrese Nombre del Producto');}
		else if(formaPago === null ){ $('#msj_compra_producto').html('Ingrese Nombre del Producto');}
		else if(formaPago == 2 && cuenta 	== ''){ $('#msj_compra_producto').html('Ingrese Cantidad');}
		else if(montopago 	== ''){ $('#msj_compra_producto').html('Ingrese Compra');}
		else{
			
			$('#msj_compra_producto').html('');

			$.ajax({
				url: 'venta/addpaymentCart',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){					
					if(data==1){						
						obtenerPagos();
					}else{
						toastr['warning']('El monto Ingresado Supera la Compra', 'Paso 3', {optionsToastr});
						obtenerPagos(); 
					}
				}
			});
		}
	});

	  
	$('#wizard-basic').on('finish.px.wizard', function(e) {

		function finishCart(){

			var formData = new FormData($("#formProveedor")[0]);
			formData.append("codLocal",$('#codLocal').val());

			$.ajax({
				url: 'venta/finishpaymentCart',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					var parsed = $.parseJSON(data);

					if(parsed[0].result === true){

						$('#formVenta')[0].reset();
						$('#formProveedor')[0].reset();
						$('#formPago')[0].reset();

						$('#wizard-basic').pxWizard('reset');
						getComboProductos($('#codLocal').val());
						obtenerCart();
						calc_total()
						obtenerPagos();
						calc_totalPagos();
						hideItems();
						tblReporteventas($('#codLocal').val());
						
						$("#idCliente").val("0000001");
						$("#cliente").val("CLIENTE GENERAL");				

						$("#pdf_viewer").html('<iframe src = "venta/creacionFacturaurl/'+parsed[0].idventa+'" width="100%"" height="600px" allowfullscreen webkitallowfullscreen></iframe>')
						$('#mDetallefactura').modal('toggle');
						
						toastr['success']('Se Ingreso la Venta con exito <br>Cod. Venta: '+parsed[0].idventa, {optionsToastr});
						
					}else{
						e.preventDefault();						
					}							
				}
			});
		}
		
		var cliente = $('#cliente').val();

		if(cliente==''){
			toastr['warning']('Ingrese un Cliente', 'Paso 4', {optionsToastr});
			e.preventDefault();
		}else if(items == 0 ){
			toastr['warning']('Ingrese un Producto', 'Paso 1', {optionsToastr});
			e.preventDefault();
		}else if(itemsPagos == 0){
			e.preventDefault();
			$.confirm({
				title: 'No se ha registrado Ningun pago, es una venta a Credito?',
				content: '¿ Desea Continuar ?',
				closeIcon: true,
				closeIconClass: 'fa fa-close' ,
				confirmButton: 'Si',
				confirmButtonClass: 'btn-primary',
				cancelButton:'No',
				icon: 'fa fa-alert',
				animation: 'zoom', 
				confirm: function(){
					finishCart();
				},cancel: function(){
					toastr['warning']('Ingrese un Pago', 'Paso 3', {optionsToastr});
					$('#wizard-basic').pxWizard('goTo', 2);
					e.preventDefault();					       
				}
			});
		}else {			
			finishCart();
		}
		// $('#wizard-finish').find('button').remove();		
	});


	  
	$('#formaPago').change(function(){
		var idProducto = $(this).val();
		if(idProducto=="02"){
			$('#divCuenta').show();
		}else{
			$('#divCuenta').hide();
			$('#cuenta').val('');
		}
	});	


	$("#ventasCart").on('click', '.btn-delete', function(){
		
		var myId = $(this).attr('id');		
		datos = { 'id': myId};	

		$.ajax({
			url: 'venta/clearproductCart',  
			type: 'POST',
			data: datos,
			success: function(data){
				obtenerCart();
				calc_total();
				obtenerPagos();
				calc_totalPagos();
			}				
		});
	});

	$('#cliente').click(function(){
	
		$("#idCliente").val("");
		$("#cliente").val("");

	})

	$("#pagosCart").on('click', '.btn-delete', function(){		
		var myId = $(this).attr('id');		
		datos = { 'id': myId};		

		$.ajax({
			url: 'venta/clearpaymentCart',  
			type: 'POST',
			data: datos,
			success: function(data){
				obtenerCart();
				calc_total();
				obtenerPagos();
				calc_totalPagos();
			}				
		});
	});

	function tblReporteventas(codLocal){
		$('#tblReporteventas').dataTable().fnDestroy();		 	
		$('#tblReporteventas').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "venta/getVentas/"+codLocal,
			"columns" : [	
				{"data" : "dFECHAVENTA"},
				{"data" : "nIDVENTA"},
				{"data" : "sCLIENTE"},
				{"data" : "sVENDEDOR"},
				{"data" : "sCostoTotalVenta"},
				{"data" : "OPCIONES"},
			],"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tblReporteventas($('#codLocal').val());


	$("#tblReporteventas").on('click', '.viewPdf', function(){
		
		var myId = $(this).attr('id');
		datos = { 'id': myId};		
 
		// $.ajax({
		// 	url: 'venta/creacionFactura',  
		// 	type: 'POST',
		// 	data: datos,
		// 	success: function(data){			
		// 	}				
		// });

		$("#pdf_viewer").html('<iframe src = "venta/creacionFacturaurl/'+myId+'" width="100%"" height="600px" allowfullscreen webkitallowfullscreen></iframe>');
		$('#mDetallefactura').modal('toggle');

	});

	
});