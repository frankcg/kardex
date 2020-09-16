$(document).on('ready',function(){

	$('#idProducto').change(function(){
		var idProducto = $(this).val();
		$.post('venta/getProductoDetalle',{
			idProducto : idProducto
		},function(data){
			$('#idProductoDetalle').html(data);
		});
	});

	$('#idProductoDetalle').change(function(){
		var idProductoDetalle = $(this).val();
		$.post('venta/getPrecios',{
			idProductoDetalle : idProductoDetalle
		},function(data){
			$('#idStock').html(data);
		});

		$.ajax({
			url: 'venta/getDetalleProducto',
			type: 'POST',
			data: {'idProductoDetalle':idProductoDetalle},
			cache: false,
			dataType : 'json',
			success: function(data){
				$('#descripcionProducto').html(data.DESCRIPCION);
			}
		});


	});

	$('#idStock').change(function(){
		var idStock = $(this).val();
		var datos = { 'idStock' : idStock}
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

	$('#cantidad').keyup(function(){
		var cantidad = $(this).val();
		var precio = $('#idStock option:selected').attr('precio');
		$('#precioVenta').val(cantidad*precio);
		$('#precioSugerido').val(cantidad*precio);
	});	

	$('#btnRegistrar').click(function(){

		var idProducto = $('#idProducto').val();
		var idProductoDetalle = $('#idProductoDetalle').val();
		var idStock = $('#idStock').val();
		var cantidad = parseInt($('#cantidad').val());		
		var precioVenta = $('#precioVenta').val();
		var stockActual = parseInt($('#stockActual').val());

		var formData = new FormData($("#formVenta")[0]);

		if(idProducto == null){ $('#msj_venta').html('Selecione Producto');}
		else if(idProductoDetalle == null){ $('#msj_venta').html('Selecione Marca');}
		else if(idStock == null || idStock == 'SELECCIONE'){ $('#msj_venta').html('Selecione Precio');}
		else if(cantidad == '' || cantidad == 0 ){ $('#msj_venta').html('Ingrese Cantidad');}
		else if(precioVenta == ''){ $('#msj_venta').html('Ingrese Precio Final');}
		else if(cantidad > stockActual){ $('#msj_venta').html('La cantidad supera al Stock Actual');}
		else{
			$('#msj_venta').html('');

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
		}
	})

});