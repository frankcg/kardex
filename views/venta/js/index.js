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

 
	
	function getComboProductos(codLocal) {
 
	$(function(){
		$.post('venta/getComboProductos/'+codLocal,{},function(data){
			console.log("entroal select");
			$('#dividProducto').show();
			$('#divProductoloading').hide();
				$('#idProducto').html(data);
				$('#idProducto').select2({
					placeholder: 'Seleccione un Producto',
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
			getComboProductos(0);
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
			console.log($('#idCuenta').val())

		},
		source: function (query, process) {
		  return $.getJSON('venta/autocuenta', { query: query }, function(data) {
			process(data)
		  })
		}   
	})

			


	$("#cuenta").keypress(function(){
		$('#idCuenta').val('');
		console.log($('#idCuenta').val())
		});

		
		$('#cliente').typeahead({
			displayText: function(item) {
				 return item.label
			},
			afterSelect: function(item) {

				this.$element[0].value = item.label
				$('#idCliente').val(item.value);
				console.log($('#idCliente').val())

			},
			source: function (query, process) {
			  return $.getJSON('venta/autocliente', { query: query }, function(data) {
				process(data)
			  })
			}   
		})

		$("#cliente").keypress(function(){
			$('#idCliente').val('');
			console.log($('#idCliente').val())
			});



	
	$('#formaPago').change(function(){
		var idProducto = $(this).val();
		console.log("change");
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
		console.log(nombrecart);
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
			 console.log(data);
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
		var formData = new FormData($("#formVenta")[0]);
		formData.append("perdida",perdida);
		$.ajax({
			url: 'venta/addproductCart',  
			type: 'POST',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){
				console.log("despuescart");
				console.log(data);
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
		
		var stockActual = parseInt(stock) - parseInt(cantidad);

		var formData = new FormData($("#formVenta")[0]);

		var outputLog = {}, iterator = formData.entries(), end = false;
		while(end == false) {
		   var item = iterator.next();
		   if(item.value!=undefined) {
		       outputLog[item.value[0]] = item.value[1];
		   } else if(item.done==true) {
		       end = true;
		   }
		    }
		
		console.log(outputLog);

		if(nombre == ''){ toastr['warning']('Ingrese un Producto', 'Paso 1', {optionsToastr});}
		else if(cantidad == ''){ toastr['warning']('Ingrese una Cantidad', 'Paso 1', {optionsToastr});}
		else if(precioCompra == ''){ toastr['warning']('Ingrese un precio', 'Paso 1', {optionsToastr});}
		else if(stockActual < 0){ toastr['warning']('la cantidad ingresada es superior al Stock', 'Paso 1', {optionsToastr});}
		else{
			$('#msj_compra').html('');

		if(precioCompra < precioVentaPromedio){
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
				console.log("entroalshow");
				$("#ventasCart").html();
				$("#ventasCart").html(data);
				calc_total()
				
			}
		})

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
		})

	}


	function calc_total(){
		var sum = 0;
		items =0;

		$(".total").each(function(){
		  sum += parseFloat($(this).text());
		  items = items + 1;
		});

		ventaTotal = sum;
		console.log(ventaTotal);

		$('#ventaTotal').text(sum);
		$('#ventaTotal2').text(sum);

		
		if(sum>0){
			showItems();
			$('#monto').text(sum);
			$('#productos').text(items);
		}
		
	  }

	  
	function calc_totalPagos(){
		var sum = 0;
		itemsPagos =0;
		$(".pagototal").each(function(){
		  sum += parseFloat($(this).text());
		  itemsPagos = itemsPagos + 1;
		});
		console.log(ventaTotal);

		pagototal = ventaTotal - sum;
		
		$('#montopago').val(pagototal);

		if(pagototal == ventaTotal){
			$('#montorestante').hide();
		}
		
		if(sum>0){
			showItems();
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
			// Validate only if jump to the forward step
			console.log(data);
			// console.log(data.activeStepIndex);
			// e.preventDefault();
			obtenerCart();
			calc_total()


			var stepIndex = $('#wizard-basic').pxWizard('getActivePane');
			console.log(stepIndex);

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
		
		console.log(outputLog);

		if(formaPago == ""){ $('#msj_compra_producto').html('Ingrese Nombre del Producto');}
		else if(formaPago === null ){ $('#msj_compra_producto').html('Ingrese Nombre del Producto');}
		else if(formaPago == 2 && cuenta 	== ''){ $('#msj_compra_producto').html('Ingrese Cantidad');}
		else if(montopago 	== ''){ $('#msj_compra_producto').html('Ingrese Compra');}
		else{
			console.log("ajax");
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
						console.log("paso Pago 1 ");
						obtenerPagos();
					}else{
						console.log("paso Pago 2");
						console.log(data)
						toastr['warning']('El monto Ingresado Supera la Compra', 'Paso 3', {optionsToastr});
						obtenerPagos();
 
					}

				}				
			});
		}
	});	



	  
	  $('#wizard-basic').on('finish.px.wizard', function(e) {
		//
		// Collect and send data...
		//

		console.log("finishbutton");
		console.log(items);
		
		var proveedor = $('#proveedor').val();

		if(proveedor==''){
			toastr['warning']('Ingrese un Proveedor', 'Paso 4', {optionsToastr});
			e.preventDefault();
		}else if(items == 0 ){
			toastr['warning']('Ingrese un Producto', 'Paso 1', {optionsToastr});
			e.preventDefault();
		}else if(itemsPagos == 0){
			toastr['warning']('Ingrese un Pago', 'Paso 3', {optionsToastr});
			e.preventDefault();
		}else {
			console.log("paso finish");
			var formData = new FormData($("#formProveedor")[0]);
			formData.append("codLocal",$('#codLocal').val());
			// e.preventDefault();

			$.ajax({
				url: 'venta/finishpaymentCart',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					console.log(data);

					var parsed = $.parseJSON(data);
					console.log(parsed[0]);
					console.log(parsed[0].result);
					console.log(parsed[0].idventa);

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
						tblReporteventas();

						$("#pdf_viewer").html('<iframe src = "venta/creacionFacturaurl/'+parsed[0].idventa+'" width="100%"" height="600px" allowfullscreen webkitallowfullscreen></iframe>')
						$('#mDetallefactura').modal('toggle');

						console.log("finishpaymentCart");
						toastr['success']('Se Ingreso la Venta con exito <br> Cod. Venta:'+parsed[0].idventa, {optionsToastr});

						
					}else{
						e.preventDefault();
						console.log("error");
					}
							
				}				
			});

			// e.preventDefault();
			
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

		console.log("click");
		var myId = $(this).attr('id');
		
		datos = { 'id': myId};
		console.log(datos)

		$.ajax({
			url: 'venta/clearproductCart',  
			type: 'POST',
			data: datos,
			success: function(data){
				console.log(data);
				console.log("entrodelete");
				obtenerCart();
			}				
		});


	});

	$("#pagosCart").on('click', '.btn-delete', function(){

		console.log("click");
		var myId = $(this).attr('id');
		
		datos = { 'id': myId};
		console.log(datos)

		$.ajax({
			url: 'venta/clearpaymentCart',  
			type: 'POST',
			data: datos,
			success: function(data){
				console.log(data);
				console.log("entrodelete");
				obtenerPagos();
			}				
		});


	})


	function tblReporteventas(){
		$('#tblReporteventas').dataTable().fnDestroy();		 	
		$('#tblReporteventas').DataTable({
			"order": [[ 0, "desc" ]],
			"ajax" : "venta/getVentas",
			"columns" : [	
				{"data" : "nIDVENTA"},
				{"data" : "nIDLOCAL"},
				{"data" : "nLOCAL"},
				{"data" : "dFECHAVENTA"},
				{"data" : "total"},
				{"data" : "OPCIONES"},
			]
		});	
	}
	
	tblReporteventas();


	$("#tblReporteventas").on('click', '.viewPdf', function(){

		console.log("click");
		var myId = $(this).attr('id');
		datos = { 'id': myId};
		console.log(datos)
 
		// $.ajax({
		// 	url: 'venta/creacionFactura',  
		// 	type: 'POST',
		// 	data: datos,
		// 	success: function(data){
			
		// 	}				
		// });

		$("#pdf_viewer").html('<iframe src = "venta/creacionFacturaurl/'+myId+'" width="100%"" height="600px" allowfullscreen webkitallowfullscreen></iframe>')

		$('#mDetallefactura').modal('toggle');



	})

	
});