$(document).on('ready',function(){

	/* ********************************************************************************************************************************
										MANTENIMIENTO PRODUCTOS
	******************************************************************************************************************************** */	

	function tablaProductos(){
		$('#tablaProductos').dataTable().fnDestroy();		 	
		$('#tablaProductos').DataTable({

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
			"ajax" : "producto/getProductos",
			"columns" : [
			{
				"data" : "CONT"
			},{
				"data" : "IDPRODUCTODETALLE"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "MARCA"
			},{
				"data" : "MODELO"
			},{
				"data" : "ESTADO"
			},{
				"data" : "FECHA"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablaProductos();

	$('#btn_add_producto').click(function(){

		var nombre = $('#nombre').val();
		var marca = $('#marca').val();
		var modelo = $('#modelo').val();

		var formData = new FormData($("#formProducto")[0]);

		if(nombre == ''){ $('#msj_usuario').html('Ingrese Nombre del Producto');}
		else if(marca == ''){ $('#msj_usuario').html('Ingrese Marca');}
		else if(modelo == ''){ $('#msj_usuario').html('Ingrese Modelo');}
		else{
			$('#msj_producto').html('');

			$.ajax({
				url: 'producto/addProducto',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					$('#m_addProducto').modal('hide');
					if(data=='ok'){
						$('#formProducto')[0].reset();
						toastr['success']('Se registro correctamente', 'Producto', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
				        //toastr['success']('Se registro correctamente', 'Usuario');
						tablaProductos();
						
					}else if(data=='error'){
						$('#msj_producto').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#msj_producto').html(data);
					}		
				}				
			});
		}
	})
	
	//BOTON NUEVO PRODUCTO
	$('#btn_nuevo_producto').click(function(){
		//$('#usuario').removeAttr("readonly")
		$('#btn_add_producto').show();
		$('#btn_update_producto').hide();
		$('#formProducto')[0].reset();
		$('#title_producto').html('Nuevo Producto');		
	});


	//TRAER EL USUARIO AL MODAL
	//$("#tablausuarios tbody").on('click','button.editusuario',function(){
	$("#tablaProductos tbody").on('dblclick','tr',function(){
		$('#btn_add_producto').hide();
		$('#btn_update_producto').show();
		$('#title_producto').html('Editar Producto');
		
		var table = $('#tablaProductos').DataTable();	
		var objeto = table.row(this).data();
		$('#idProducto').val(objeto.IDPRODUCTO);
		$('#idProductoDetalle').val(objeto.IDPRODUCTODETALLE);
		$('#nombre').val(objeto.NOMBRE);
		$('#marca').val(objeto.MARCA);
		$('#modelo').val(objeto.MODELO);
		$('#descripcion').val(objeto.DESCRIPCION);

		$('#m_addProducto').modal('show');

	});


	//DESHABILITAR USUARIO
	$("#tablaProductos tbody").on('click','button.desactivarProducto',function(){

		var idProducto = $(this).attr("idProducto");
		var idProductoDetalle = $(this).attr("idProductoDetalle");

		$.confirm({
			title: 'Inactivar Producto !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('producto/cambiarEstadoProducto',{
					idProducto : idProducto,
					idProductoDetalle : idProductoDetalle,
					estado : 0
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Inactivo Correctamente !!');						
						tablaProductos();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	//Habilitar
	$("#tablaProductos tbody").on('click','button.activarProducto',function(){

		var idProducto = $(this).attr("idProducto");
		var idProductoDetalle = $(this).attr("idProductoDetalle");

		$.confirm({
			title: 'Habilitar Producto !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('producto/cambiarEstadoProducto',{
					idProducto : idProducto,
					idProductoDetalle : idProductoDetalle,
					estado : 1
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se habilito Correctamente !!');						
						tablaProductos();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	

	//ACTUALIZAR USUARIO
	$('#btn_update_producto').click(function(){

		var nombre = $('#nombre').val();
		var marca = $('#marca').val();
		var modelo = $('#modelo').val();

		var formData = new FormData($("#formProducto")[0]);

		if(nombre == ''){ $('#msj_usuario').html('Ingrese Nombre del Producto');}
		else if(marca == ''){ $('#msj_usuario').html('Ingrese Marca');}
		else if(modelo == ''){ $('#msj_usuario').html('Ingrese Modelo');}
		else{
			$('#msj_producto').html('');

			$.ajax({
				url: 'producto/updateProducto',
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					$('#m_addProducto').modal('hide');
					if(data){						
				        toastr['success']('Se actualizo correctamente', 'Producto', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
						tablaProductos();
						
					}else{
						$('#msj_producto').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}	
				}				
			});
		}
		
	});

	

	/* ********************************************************************************************************************************
										MANTENIMIENTO STOCK
	******************************************************************************************************************************** */

	$('#producto').change(function(){
		var idProducto = $(this).val();
		$.post('producto/getMarcas',{
			idProducto : idProducto
		},function(data){
			//console.log(data);
			$('#marcaStock').html(data);
		});
	});

	function tablaStock(){
		$('#tablaStock').dataTable().fnDestroy();		 	
		$('#tablaStock').DataTable({

			//PARA EXPORTAR
			/*dom: "Bfrtip",
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

			"order" : [ [ 0, "asc" ] ],
			"ajax" : "producto/getStocks",
			"columns" : [{
				"data" : "CONT"
			},{
				"data" : "IDSTOCK"
			},{
				"data" : "IDPRODUCTODETALLE"
			},{
				"data" : "PRODUCTO"
			},{
				"data" : "MARCA"
			},{
				"data" : "MODELO"
			},{
				"data" : "CANTIDAD"
			},{
				"data" : "PRECIO_VENTA"
			},{
				"data" : "INVERSION"
			},{
				"data" : "GANANCIA"
			},{
				"data" : "FECHA"
			},{
				"data" : "OPCIONES"
			},			
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}   
	
	tablaStock();	
	
	// NUEVO PERFIL
	$('#btn_nuevo_stock').click(function(){		
		$('#btn_add_stock').show();
		$('#btn_update_stock').hide();
		$('#title_stock').html('Agregar Stock');
		$('#formStock')[0].reset();
	});

	// GUARDAR PERFIL
	$('#btn_add_stock').click(function(){

		var producto = $('#producto').val()
		var marca = $('#marcaStock').val()
		var cantidad = $('#cantidad').val()
		var precio = $('#precio').val()
		var inversion = $('#inversion').val()

		var formData = new FormData($("#formStock")[0]);

		if(producto == null){ $('#msj_stock').html('Selecione Producto');}
		else if(marca == null){ $('#msj_stock').html('Selecione Producto');}
		else if(cantidad == ''){ $('#msj_stock').html('Ingrese Cantidad');}
		else if(precio == ''){ $('#msj_stock').html('Ingrese Precio');}
		else if(inversion == ''){ $('#msj_stock').html('Ingrese Inversion');}
		else{
			$('#msj_stock').html('');

			$.ajax({
				url: 'producto/addStock',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){									
					if(data=='ok'){
						toastr['success']('Se registro correctamente', 'Stock', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
						$('#formStock')[0].reset();
						$('#addStock').modal('hide');
						tablaStock();
					}else if(data=='error'){
						$('#msj_stock').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#msj_stock').html(data);
					}				
				}				
			});
		}
	});

	// INHABILITAR PERFIL
	$("#tablaStock tbody").on('click','button.deleteStock',function(){
		
		var idStock =  $(this).attr("id");

		$.confirm({
			title: 'Eliminar Stock !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('producto/deleteStock',{
					idStock : idStock,
					estado : 0
				},function(data){		 	
					if(data){
						$.alert('Se elimino Correctamente !!');						
						tablaStock();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});	
	});


});