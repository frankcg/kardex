$(document).on('ready',function(){

	$('#idProducto').change(function(){
		var idProducto = $(this).val();
		$.post('venta/getCompras',{
			idProducto : idProducto
		},function(data){
			$('#idCompra').html(data);
		});
	});	

	$('#idCompra').change(function(){
		var idCompra = $(this).val();
		var datos = { 'idCompra' : idCompra}
		$.ajax({
			url: 'venta/getStockActual',
			type: 'POST',
			data: datos,
			cache: false,
			dataType : 'json',
			success: function(data){
				$('#stockActual').val(data.STOCK_ACTUAL);
			}
		});
	});

	$('#cantidad, #precioVenta').keyup(function(){
		var cantidad = $('#cantidad').val();
		var precioVenta = $('#precioVenta').val();
		//var precio = $('#idStock option:selected').attr('precio');
		$('#precioTotal').val(cantidad*precioVenta);		
	});	

	$('#btnRegistrar').click(function(){

		var idProducto = $('#idProducto').val();
		var idCompra = $('#idCompra').val();
		var cantidad = parseInt($('#cantidad').val());		
		var precioVenta = $('#precioVenta').val();
		var stockActual = parseInt($('#stockActual').val());

		var formData = new FormData($("#formVenta")[0]);

		if(idProducto == null){ $('#msj_venta').html('Selecione Producto');}		
		else if(idCompra == null || idCompra == 'SELECCIONE'){ $('#msj_venta').html('Selecione Alias');}
		else if(cantidad == '' || cantidad == 0 ){ $('#msj_venta').html('Ingrese Cantidad');}
		else if(precioVenta == ''){ $('#msj_venta').html('Ingrese Precio Final');}
		else if(cantidad > stockActual){ $('#msj_venta').html('La cantidad supera al Stock Actual');}
		else{
			$('#msj_venta').html('');

			$.confirm({
				title: 'Registrando Compra !!',
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
						url: 'venta/addVenta',  
						type: 'POST',
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						success: function(data){
							//alert(data);				
							if(data=='ok'){
								$('#formVenta')[0].reset();
								toastr['success']('Se registro correctamente', 'Venta', {
						          closeButton: true,
						          progressBar: true,
						          preventDuplicates: true,
						          newestOnTop: true,
						        });
							}else if(data=='error'){
								$('#msj_venta').html('Ha ocurrido un Error. Intente de Nuevo!!');
							}else{
								$('#msj_venta').html(data);
							}		
						}				
					});
				},cancel: function(){
					$.alert('Cancelado');		        
				}
			});
		}
	})

});