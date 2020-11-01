$(document).on('ready',function(){


	optionsToastr = {	closeButton: true,
						progressBar: true,
						preventDuplicates: true,
						newestOnTop: true,};
			
						
	$(function(){
		$.post('compra/getTipopago',{},function(data){
				$('#formaPago').html(data);
		});
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



	$(function() {

		$('#nombre').typeahead({
			displayText: function(item) {
				 return item.label
			},
			afterSelect: function(item) {

				this.$element[0].value = item.label
				$('#idProducto').val(item.value);
				console.log($('#idProducto').val())

				datos = {'id': item.value};

				console.log($('#idProducto').val())

				$.ajax({
					url: 'compra/promCompraProductos',  
					type: 'POST',
					data: datos,
					success: function(data){
						$("#divPreciosAnt").show();
						$("#htmlPrecios").html('');
						$("#htmlPrecios").html(data);
						if(data == ''){
							$("#htmlPrecios").html('<div class="widget-notifications-description">Sin Data de Compra</div>');
						}				
					}
				})


			},
			source: function (query, process) {
			  return $.getJSON('compra/autocomplete', { query: query }, function(data) {
				process(data)
			  })
			}   
		})


		$("#nombre").keypress(function(){
		$('#idProducto').val('');
		console.log($('#idProducto').val())
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
			  return $.getJSON('compra/autocuenta', { query: query }, function(data) {
				process(data)
			  })
			}   
		})

		$("#cuenta").keypress(function(){
			$('#idCuenta').val('');
			console.log($('#idCuenta').val())
			});



		$("#idProveedor").val("0001");
		$("#proveedor").val("PROVEEDOR GENERAL");

		$('#proveedor').typeahead({
			displayText: function(item) {
				 return item.label
			},
			afterSelect: function(item) {

				this.$element[0].value = item.label
				$('#idProveedor').val(item.value);
				console.log($('#idProveedor').val())

			},
			source: function (query, process) {
			  return $.getJSON('compra/autoproveedor', { query: query }, function(data) {
				process(data)
			  })
			}   
		})

		$("#proveedor").keypress(function(){
			$('#idProveedor').val('');
			console.log($('#idProveedor').val())
			});




	  });

	  function hideItems() {
		$('#productos').hide();
		$('#monto').hide();
		$('#divCuenta').hide();
		$('#montorestante').hide();
		$('#divPreciosAnt').hide();
		
	  };

	  hideItems();

	  function showItems() {
		$('#productos').show();
		$('#monto').show();
	  };
	  
	  function showItemsPaso3() {
		$('#montorestante').show();
	  };

	  $(function() {

		$('#wizard-basic').pxWizard();
		  $('#wizard-basic').on('stepchange.px.wizard', function(e, data) {
			
			if (data.nextStepIndex == 1 ) { 
				obtenerCart();
			}

			var stepIndex = $('#wizard-basic').pxWizard('getActivePane');
			console.log(stepIndex);

			if (data.nextStepIndex == 2 ) { 
				obtenerPagos();
				calc_totalPagos();
				var montopagar = $('#monto').text();
				$('#montopago').val(montopagar);
			}


		  });
	  });

	  
	  $('#wizard-basic').on('finish.px.wizard', function(e) {
		//
		// Collect and send data...
		//

		function finishCart(){
	
			var codLocal = $('#codLocal').val();
			var formData = new FormData($("#formProveedor")[0]);
			formData.append("codLocal",codLocal); 

			$.ajax({
				url: 'compra/finishpaymentCart',  
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
					console.log(parsed[0].idCompra);

					if(parsed[0].result === true){

						$('#formCompra')[0].reset();
						$('#formProveedor')[0].reset();
						$('#formPago')[0].reset();

						$('#wizard-basic').pxWizard('reset');
						
						obtenerCart();
						obtenerPagos();
						calc_totalPagos();
						hideItems();
						
						$("#idProveedor").val("0001");
						$("#proveedor").val("PROVEEDOR GENERAL");
						console.log("finishpaymentCart");
						toastr['success']('Se Ingreso la compra con exito <br> Cod. Compra:'+parsed[0].idCompra, {optionsToastr});

					}else{
						e.preventDefault();
						console.log("error");
					}
							
				}				
			});

		}

		console.log("finishbutton");
		console.log(items);

		var proveedor = $('#proveedor').val();

		calc_totalPagos();
 

		if(proveedor==''){
			toastr['warning']('Ingrese un Proveedor', 'Paso 4', {optionsToastr});
			e.preventDefault();
		}else if(items == 0 ){
			toastr['warning']('Ingrese un Producto', 'Paso 1', {optionsToastr});
			e.preventDefault();
		}else if(itemsPagos == 0){
			e.preventDefault();
			$.confirm({
				title: 'No se ha registrado Ningun pago, es una Compra a Credito?',
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
			console.log("paso finish");
			finishCart();			
		}
		


		// $('#wizard-finish').find('button').remove();

		
	  });



	  obtenerCart();
	  calc_total()
	  obtenerPagos();
	  calc_totalPagos();


	/* ********************************************************************************************************************************
										Funciones
	******************************************************************************************************************************** */	
	function obtenerCart(){
		$.ajax({
			url: 'compra/showproductCart',  
			type: 'GET',
			data: html,
			success: function(data){
				$("#comprasCart").html();
				$("#comprasCart").html(data);
				calc_total()
				
			}
		})

	}

	function obtenerPagos(){
		$.ajax({
			url: 'compra/showpaymentCart',  
			type: 'GET',
			data: html,
			success: function(data){
				$("#pagosCart").html();
				$("#pagosCart").html(data);
				calc_totalPagos	()
				
				
			}
		})

	}



	$('#acuenta').click(function(){
		if($(this).is(":checked")){
			console.log("Checkbox is checked.");
		}
		else if($(this).is(":not(:checked)")){
			console.log("Checkbox is unchecked.");
		}
	});



	$("#mAddCompra").on("shown.bs.modal", function () { 
		obtenerCart();
		calc_total()
	});

	function calc_total(){
		var sum = 0;
		items =0;
		$(".total").each(function(){
		  sum += parseFloat($(this).text());
		  items = items + 1;
		});

		compraTotal = sum;
		$('#compraTotal').text(sum);
		
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
		
		pagoRestantetotal = compraTotal - sum;

		if(pagoRestantetotal == 0 ){
			$('#montorestante').removeClass('label-warning').addClass('label-success');
		}else if (pagoRestantetotal>0){
			$('#montorestante').removeClass('label-success').addClass('label-warning');
		}
		console.log(sum);

		if(sum > 0){
			console.log("Paso pago restante");
			showItemsPaso3();
			$('#montorestante').text(pagoRestantetotal);
			$('#montopago').val(pagoRestantetotal);

		}else{
			$('#montorestante').text(pagoRestantetotal);
			$('#montopago').val(pagoRestantetotal);
		}


	  }

	  $("#divPreciosAnt").on('click', '.etPrecio', function(){
		var valor = $(this).text();
		$('#precioCompra').val(valor);
		})


	
	  $("#comprasCart").on('click', '.btn-delete', function(){

		console.log("click");
		var myId = $(this).attr('id');
		
		datos = { 'id': myId};
		console.log(datos)

		$.ajax({
			url: 'compra/clearproductCart',  
			type: 'POST',
			data: datos,
			success: function(data){
				console.log(data);
				console.log("entrodelete");
				obtenerCart();
				calc_total();
				obtenerPagos();
				calc_totalPagos();
			}				
		});


	})

	$("#pagosCart").on('click', '.btn-delete', function(){

		console.log("click");
		var myId = $(this).attr('id');
		
		datos = { 'id': myId};
		console.log(datos)

		$.ajax({
			url: 'compra/clearpaymentCart',  
			type: 'POST',
			data: datos,
			success: function(data){
				console.log(data);
				console.log("entrodelete");
				obtenerCart();
				calc_total();
				obtenerPagos();
				calc_totalPagos();
			}				
		});


	})




	$('#btn_add_compra').click(function(){

		var nombre = $('#nombre').val();
		var cantidad = $('#cantidad').val();
		var precioCompra = $('#precioCompra').val();
		var aliasCompra = $('#aliasCompra').val();
		
		console.log(nombre);

		var formData = new FormData($("#formCompra")[0]);

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

		if(nombre == ''){ $('#msj_compra').html('Ingrese Nombre del Producto');}
		else if(cantidad == ''){ $('#msj_compra').html('Ingrese Cantidad');}
		else if(precioCompra == ''){ $('#msj_compra').html('Ingrese Compra');}
		else if(aliasCompra == ''){ $('#msj_compra').html('Ingrese Alias');}
		else{
			$('#msj_compra').html('');

			$.ajax({
				url: 'compra/addproductCart',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					
					obtenerCart();
					console.log("despuescart");
					console.log(data)
					
					// $('#mAddCompra').modal('hide');
					// if(data=='ok'){
					// 	$('#formCompra')[0].reset();
					// 	toastr['success']('Se registro correctamente', 'Compra', {
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
	});

	$('#btn_add_pago').click(function(){

		var formaPago 		= $('#formaPago').val();
		var cuenta 			= $('#cuenta').val();
		var montopago 		= $('#montopago').val();

		var formData = new FormData($("#formPago")[0]);

		console.log(formaPago); 

		formData.append("montoapagar", compraTotal);
 
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

		if(formaPago 	== '' || formaPago 	=== null ){ toastr['warning']('La Forma de pago no puede ser nula', 'Paso 3', {optionsToastr});}
		else if(formaPago == 2 && cuenta 	== ''){ toastr['warning']('La cuenta no puede ser nula', 'Paso 3', {optionsToastr});}
		else if(montopago 	== ''){ toastr['warning']('Por Favor Ingrese un Monto de Pago', 'Paso 3', {optionsToastr});}
		else{
			console.log("ajax");
			$('#msj_compra_producto').html('');

			$.ajax({
				url: 'compra/addpaymentCart',  
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
	
});