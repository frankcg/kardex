$(document).on('ready',function(){


	optionsToastr = {	closeButton: true,
						progressBar: true,
						preventDuplicates: true,
						newestOnTop: true,};
			
						
	$(function(){
		$.post('venta/getComboProductos',{},function(data){
				$('#nombre').html(data);
				$('#nombre').select2({
					placeholder: 'Seleccione un Producto',
					dropdownAutoWidth : 'true',
				  });
		});
	});

	
	$('#nombre').change(function(){
		var nombrecart = $('#nombre').find(':selected').attr('id2');
		$('#nombrecart').val(nombrecart);
		console.log(nombrecart);
	});


	$('#btn_add_compra').click(function(){
		
		var nombre 			= $('#nombrecart').val();
		var idnombre 		= $('#nombre').find(':selected').attr('value');
		var cantidad 		= $('#cantidad').val();
		var stock 			= $('#nombre').find(':selected').attr('id3');
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

			$.ajax({
				url: 'venta/addproductCart',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					
					obtenerCart();
					calc_total()
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




	$('#formaPago').change(function(){
		var idProducto = $(this).val();
		if(idProducto=="2"){
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
		
	  };

	  hideItems();

	  function showItems() {
		$('#productos').show();
		$('#monto').show();
	  };
	  
	  

	  $(function() {

		$('#wizard-basic').pxWizard();
		  $('#wizard-basic').on('stepchange.px.wizard', function(e, data) {
			// Validate only if jump to the forward step
			console.log(data);
			// console.log(data.activeStepIndex);
			// e.preventDefault();
			obtenerCart();
			calc_total()
			obtenerPagos();
			calc_totalPagos();

			var stepIndex = $('#wizard-basic').pxWizard('getActivePane');
			console.log(stepIndex);

			// if (data.nextStepIndex < data.activeStepIndex) { return; }
			// var $form = $('#wizard-basic').pxWizard('getActivePane');
			// if (!$form.valid()) {
			//   e.preventDefault();
			// }

		  });


		



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
		}else if(items = 0 ){
			toastr['warning']('Ingrese un Producto', 'Paso 1', {optionsToastr});
			e.preventDefault();
		}else if(itemsPagos = 0){
			toastr['warning']('Ingrese un Pago', 'Paso 3', {optionsToastr});
			e.preventDefault();
		}else {
			console.log("paso finish");
			var formData = new FormData($("#formProveedor")[0]);
			$.ajax({
				url: 'compra/finishpaymentCart',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					console.log(data)
					if(data=="1"){
						$('#formCompra')[0].reset();
						$('#formProveedor')[0].reset();
						$('#formPago')[0].reset();

						$('#wizard-basic').pxWizard('reset');
						
						obtenerCart();
						calc_total()
						obtenerPagos();
						calc_totalPagos();
						hideItems();
						console.log("finishpaymentCart");
						
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



	  obtenerCart();
	  calc_total()


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
				calc_total()
				
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
		  items = items + 1;
		});
		// $('#compraTotal').text(sum);
		console.log(sum);
		if(sum>0){
 
			// $('#monto').text(sum);
			// $('#productos').text(items);
		}
		
	  }


	
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
				obtenerPagos();
			}				
		});


	})




	$('#btn_add_pago').click(function(){

		var formaPago 		= $('#formaPago').val();
		var cuenta 			= $('#cuenta').val();
		var montopago 		= $('#montopago').val();

		var formData = new FormData($("#formPago")[0]);

		// var outputLog = {}, iterator = formData.entries(), end = false;
		// while(end == false) {
		//    var item = iterator.next();
		//    if(item.value!=undefined) {
		//        outputLog[item.value[0]] = item.value[1];
		//    } else if(item.done==true) {
		//        end = true;
		//    }
		//     }
		
		// console.log(outputLog);

		if(formaPago 	== ''){ $('#msj_compra_producto').html('Ingrese Nombre del Producto');}
		else if(formaPago == 2 && cuenta 	== ''){ $('#msj_compra_producto').html('Ingrese Cantidad');}
		else if(montopago 	== ''){ $('#msj_compra_producto').html('Ingrese Compra');}
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
					console.log("despuescart");
					console.log(data)
					obtenerPagos();
				}				
			});
		}



	});	
	
});